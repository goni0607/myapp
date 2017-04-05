<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {
        $this->cache = app('cache');

        if ((new \ReflectionClass($this))->implementsInterface(Cacheable::class) and taggable()) {
            $this->cache = app('cache')->tags($this->cacheTags());
        }
    }


    protected function cache($key, $minutes, $query, $method, ...$args)
    {
    		$args = (!empty($args)) ? implode(',', $args) : null;

    		if (config('project.cache') === false) {
    			return $query->{$method}($args);
    		}

    		$cache = taggable() ? app('cache')->tags('???') : app('cache');

    		return $cache->remember($key, $minutes, function () use($query, $method, $args) {
    			return $query->{$method}($args);
    		});
    		
    		/* == memcached를 사용하면서 주석 처리함.
    		return \Cache::remember($key, $minutes, function () use($query, $method, $args) {
    			return $query->{$method}($args);
    		});
    		*/
    }
}
