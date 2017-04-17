<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Http\Requests\ArticlesRequest;

class ArticlesController extends Controller implements Cacheable
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    public function cacheTags()
    {
        return 'articles';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $slug = null)
    {
        // 즉시로드 예제 - with 메소드 사용.
        //$articles = \App\Article::with('user')->get();

        // 지연로드 예제 - load 메소드 사용.
        //$articles = \App\Article::get();
        //$articles->load('user');
        
        $cacheKey = cache_key('articles.index');

        $query = $slug ? \App\Tag::whereSlug($slug)->firstOrFail()->articles() : new \App\Article;

        $query = $query->orderBy(
            $request->input('sort', 'created_at'),
            $request->input('order', 'desc')
        );

        if ($keyword = request()->input('q')) {
            $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
            $query = $query->whereRaw($raw, [$keyword]);
        }

        //$articles = $query->latest()->paginate(5);
        //$articles = $query->paginate(3);
        $articles = $this->cache($cacheKey, 5, $query, 'paginate', 3);

        // 페이지네이터 예제
        // $articles = \App\Article::latest()->paginate(5);
        //$articles->load('user');

        //dd(view('articles.index', compact('articles'))->render());
        return $this->respondCollection($articles);
    }

    protected function respondCollection(LengthAwarePaginator $articles)
    {
        return view('articles.index', compact('articles'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $article = new \App\Article;

        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticlesRequest $request)
    {
        /*
        $rules = [
            'title' => ['required'],
            'content' => ['required', 'min:10'],
        ];

        $messages = [
            'title.required' => '제목은 필수 입력 항목입니다.',
            'content.required' => '본문은 필수 입력 항목입니다.',
            'content.min' => '본문은 최소 :min 글자 이상이 필요합니다.',
        ];

        
        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->validate($request, $rules, $messages);
        */
        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);

        $article = $request->user()->articles()->create($payload);
        //$article = \App\User::whereEmail(auth()->user()->email)->articles()->create($request->all());

        if (!$article) {
            return back()->with('flash_message', '글이 저장되지 않았습니다.')->withInput();
        }

        $article->tags()->sync($request->input('tags'));

        /* -- 파일첨부 테스트
        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach($files as $file) {
                $filename = str_random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
                $file->move(attachments_path(), $filename);

                $article->attachments()->create([
                    'filename' => $filename,
                    'bytes' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                ]);
            }
        }

        var_dump('이벤트를 던집니다.');
        //event('article.created', [$article]); -- Event 클래스를 사용하지 않고자 문자열로 이벤트를 사용할 경우
        event(new \App\Events\ArticleCreated($article));
        var_dump('이벤트를 던졌습니다.');
        */

        event(new \App\Events\ArticlesEvent($article));
        // event(new \App\Events\ModelChanged(['articles']));

        // return redirect(route('articles.show', $article->id))->with('flash_message', '작성하신 글이 저장되었습니다.');
        return $this->respondCreated($article);

        //return __METHOD__ . '은(는) 사용자의 입력한 폼 데이터로 새로운 Article 컬렉션을 만듭니다.';
    }

    protected function respondCreated(\App\Article $article)
    {
        flash()->success(trans('forum.articles.success_writing'));

        return redirect('articles.show', $article->id);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Article $article)
    {
        //$article = \App\Article::findOrFail($id);
        //dd($article);
        //debug($article->toArray());
        $article->view_count += 1;
        $article->save();

        $comments = $article->comments()
                                ->with('replies')
                                ->withTrashed()
                                ->whereNull('parent_id')
                                ->latest()->get();
        
        return view('articles.show', compact('article', 'comments'));

        //return $article->toArray();

        //return $article->title;

        //return __METHOD__ . '은(는) 다음 기본 키를 가진 Article 모델을 조회합니다.' . $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Article $article)
    {
        $this->authorize('update', $article);

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \App\Article $article)
    {
        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);

        $article->update($payload);
        $article->tags()->sync($request->input('tags'));

        flash()->success('수정하신 내용을 저장했습니다.');

        event(new \App\Events\ModelChanged(['articles']));

        return redirect(route('articles.show', $article->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        event(new \App\Events\ModelChanged(['articles']));

        return response()->json([], 204);
    }
}
