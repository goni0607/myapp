<?php

return [
	'name' => 'My Application',
	'url' => 'http://myapp.dev:8000',
	'api_domain' => 'api.myapp.dev',
	'app_domain' => 'myapp.dev',
	'description' => '',

    /*
    |--------------------------------------------------------------------------
    | Tag 목록
    |--------------------------------------------------------------------------
    */
    'tags' => [
        'laravel' => '라라벨',
        'lumen' => '루멘',
        'general' => '자유의견',
        'server' => '서버',
        'tip' => '팁',
    ],

    /*
    |--------------------------------------------------------------------------
    | 검색 컬럼
    |--------------------------------------------------------------------------
    */
    'sorting' => [
        'view_count' => '조회수',
        'created_at' => '작성일',
    ],

    /*
    |--------------------------------------------------------------------------
    | 지원하는 언어 목록
    |--------------------------------------------------------------------------
    */
    'locales' => [
        'ko' => '한국어',
        'en' => 'English',
    ],

    /*
    |--------------------------------------------------------------------------
    | 캐시
    |--------------------------------------------------------------------------
    */
    'cache' => true,
];