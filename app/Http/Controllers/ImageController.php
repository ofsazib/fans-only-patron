<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use League\Glide\ServerFactory;
use Illuminate\Support\Facades\Storage;
use League\Glide\Signatures\SignatureFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\Signatures\SignatureException;
use League\Glide\Responses\LaravelResponseFactory;

class ImageController extends Controller
{
	public function picture(Filesystem $filesystem, $path)
	{

		
		$server = ServerFactory::create([
			'response' => new LaravelResponseFactory(app('request')),
			'source' => $filesystem->getDriver(),
			'cache' => $filesystem->getDriver(),
			'cache_path_prefix' => '.cache',
			'base_url' => 'usermedia',
		]);

		// signature
		$signkey = env('APP_KEY');

		// validate image size via signature, idiots won't be able to change width/height/etc.
		try {

			// validate signature
			SignatureFactory::create($signkey)->validateRequest('usermedia/' . $path, request()->all());

			// output image
			$server->outputImage($path, request()->all());

			// delete cache for the "smart" guys who will try to access the image via cache
			$server->deleteCache($path);

		} catch (\Exception $e) {

			// delete cache, just in case it was saved
			$server->deleteCache($path);

			// return image
			$im = imagecreatetruecolor(860, 460);
			$text_color = imagecolorallocate($im, 233, 14, 91);

			imagestring($im, 1, 350, 250,  $e->getMessage(), $text_color);

			// Set the content type header - in this case image/jpeg
			header('Content-Type: image/jpeg');

			// Output the image
			imagejpeg($im);

			// Free up memory
			imagedestroy($im);
		}
	}
}
