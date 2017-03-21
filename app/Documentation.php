<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;

class Documentation extends Model
{
	public function get($file = 'documentation.md')
	{
		$content = File::get($this->path($file));

		return $this->replaceLinks($content);
	}


	protected function path($file)
	{
		$file = ends_with($file, '.md') ? $file : $file . '.md';
		$path = base_path('docs' . DIRECTORY_SEPARATOR . $file);

		if (!File::exists($path)) {
			abort(404, 'File Not Found.');
		}

		return $path;
	}


	protected function replaceLinks($content)
	{
		return str_replace('/docs/{{version}}', '/docs', $content);
	}
}
