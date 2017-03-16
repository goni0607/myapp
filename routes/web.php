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

Route::get('/', function () {
	$items = ['apple', 'banana', 'tomato'];
    return view('welcome')->with([
    		'name' => 'triplek',
    		'greeting' => '안녕하세요?',
    		'items' => $items
    		]);
});

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