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

Route::get('/', 'HomeController@index');

Route::resource('articles', 'ArticlesController');

Route::resource('attachments', 'AttachmentsController', ['only' => ['store', 'destroy']]);

/* 라라벨 내장 인증에서 설치한 라우트 삭제 */
//Auth::routes();

/* 사용자 가입 */
Route::get('auth/register', [
	'as'		=> 'users.create',
	'uses'	=> 'UsersController@create',
]);
Route::post('auth/register', [
	'as'		=> 'users.store',
	'uses'	=> 'UsersController@store',
]);
Route::get('auth/confirm/{code}',[
	'as'		=> 'users.confirm',
	'uses'	=> 'UsersController@confirm',
])->where('code', '[\pL-\pN]{60}');

/* 사용자 인증 */
Route::get('login', [
	'as'		=> 'login',
	'uses'	=> 'SessionsController@create',
]);
Route::get('auth/login', [
	'as'		=> 'sessions.create',
	'uses'	=> 'SessionsController@create',
]);
Route::post('auth/login', [
	'as'		=> 'sessions.store',
	'uses'	=> 'SessionsController@store',
]);
Route::post('auth/logout', [
	'as'		=> 'sessions.destroy',
	'uses'	=> 'SessionsController@destroy',
]);

/* 비밀번호 조회 */
Route::get('auth/remind', [
	'as'		=> 'remind.create',
	'uses'	=> 'PasswordsController@getRemind',
]);
Route::post('auth/remind', [
	'as'		=> 'remind.store',
	'uses'	=> 'PasswordsController@postRemind',
]);
Route::get('auth/reset/{token}', [
	'as'		=> 'reset.create',
	'uses'	=> 'PasswordsController@getReset',
])->where('token', '[\pL-\pN]{64}');
Route::post('auth/reset', [
	'as'		=> 'reset.store',
	'uses'	=> 'PasswordsController@postReset',
]);

/* tag */
Route::get('tags/{slug}/articles', [
	'as'		=> 'tags.articles.index',
	'uses'	=> 'ArticlesController@index',
]);

/* Social Login */
Route::get('social/{provider}', [
	'as'		=> 'social.login',
	'uses'	=> 'SocialController@execute',
]);

/* 언어 선택 */
Route::get('locale', [
    'as' => 'locale',
    'uses' => 'WelcomeController@locale',
]);

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

Route::get('docs/{file?}', 'DocsController@show');

Route::get('docs/images/{image}', 'DocsController@image')->where('image', '[\pL-\pN\._-]+-img-[0-9]{2}.png');

/*
Route::get('docs/{file?}', function ($file = null) {
	$text = (new App\Documentation)->get($file);

	return app(ParsedownExtra::class)->text($text);
});
*/

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

Route::get('/home', 'HomeController@index');
