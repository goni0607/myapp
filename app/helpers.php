<?php

if (!function_exists('markdown')) {
	function markdown($text = null)
	{
		return app(ParsedownExtra::class)->text($text);
	}
}


if (! function_exists('current_url')) {
    /**
     * Build current url string, without return param.
     *
     * @return string
     */
    function current_url()
    {
        if (! request()->has('return')) {
            return request()->fullUrl();
        }

        return sprintf(
            '%s?%s',
            request()->url(),
            http_build_query(request()->except('return'))
        );
    }
}


if (! function_exists('is_api_domain')) {
    /**
     * Determine if the current request is for HTTP api.
     *
     * @return bool
     */
    function is_api_domain()
    {
        return starts_with(request()->getHttpHost(), config('project.api_domain'));
    }
}