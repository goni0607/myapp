<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index');

Route::resource('articles', 'ArticlesController');

Route::get('mail', function () {
	$article = App\Article::with('user')->find(1);

	return Mail::send(
		'emails.articles.created',
		compact('article'),
		function ($message) use ($article) {
			$message->from('triplek@triplek.net', 'triplek');
			$message->to(['triplek@triplek.net', 'goni0607@naver.com']);
			$message->subject('새 글이 등록되었습니다.-' . $article->title);
			$message->cc('triplek@weonit.co.kr');
			$message->attach(storage_path('kittycheki.png'));
		}
	);
});

Route::get('markdown', function () {
	$text =<<<EOT
# 마크다운 예제 1

이 문서서는[마크다운][1]으로 썼습니다. 화면에는 HTML로 변환되어 출력됩니다.

## 순서 없는 목록

- 첫 번째 항목
- 두 번째 항목[^1]

[1]: http://daringfireball.net/projects/markdown

[^1]: 두 번째 항목_ http://google.com

EOT;

	return app(ParsedownExtra::class)->text($text);
});
/* debuging query. 
DB::listen(function ($query) {
	//var_dump($query->sql);
	var_dump($query->sql);
});
*/

/* Route for user authorization */
/*
Route::get('auth/login', function() {
	$credentials = [
		'email' => 'John@example.com',
		'password' => 'password'
	];

	if (!auth()->attempt($credentials, true)) {
		return '로그인 정보가 정확하지 않습니다.';
	}

	return redirect('protected');
});

Route::get('protected', function() {
	dump(session()->all());

	if (!auth()->check()) {
		return 'Who are you?';
	}

	return 'Welcome ' . auth()->user()->name;
});

Route::get('auth/logout', function() {
	auth()->logout();

	return 'See you next';
});
*/

/*
Route::get('/', function () {
	$items = ['apple', 'banana', 'tomato'];
    return view('welcome')->with([
    		'name' => 'triplek',
    		'greeting' => '안녕하세요?',
    		'items' => $items
    		]);
});
*/

/*
// route 이름 변경

Route::get('/', [
	'as' => 'home',
	function (){
		return '제 이름은 "home" 입니다.';
	}
]);

Route::get('/home', function() {
	return redirect(route('home'));
});
*/

/*

Route::get('/', function () {
    return view('welcome');
});

*/


/*

// 패턴 사용 라우팅 예 1

Route::get('/{foo?}', function ($foo = 'bar') {
	return $foo;
})->where('foo', '[0-9a-zA-Z]{3}');


// 패턴 사용 라우팅 예 2

Route::pattern('foo', '[0-9a-zA-Z]{3}');

Route::get('/{foo?}', function ($foo = 'bar') {
	return $foo;
});
*/

/*
Route::get('/{foo}', function ($foo) {
	return $foo;
});

Route::get('/{foo?}', function ($foo = 'bar') {
	return $foo;
});
*/
Auth::routes();

Route::get('/home', 'HomeController@index');
