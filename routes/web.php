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


// debuging query.
DB::listen(function ($query) {
	//var_dump($query->sql);
	var_dump($query->sql);
});


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
