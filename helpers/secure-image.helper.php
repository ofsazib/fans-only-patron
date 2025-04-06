<?php

use League\Glide\Urls\UrlBuilderFactory;

if (!function_exists('secure_image')) {

	function secure_image($image, $width, $height)
	{

		// get security key
		$signkey = env('APP_KEY');

		// build url
		$urlBuilder = UrlBuilderFactory::create('usermedia', $signkey);

		return $urlBuilder->getUrl($image, ['w' => $width, 'h' => $height, 'fit' => 'crop']);
	}
}
