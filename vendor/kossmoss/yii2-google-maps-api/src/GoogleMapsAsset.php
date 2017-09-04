<?php

namespace kossmoss\GoogleMaps;

/**
 * Asset for Gmap3 JavaScript library
 * @link https://github.com/kossmoss/yii2-google-maps-api
 * @copyright Copyright (c) 2016 kossmoss <radiokoss@gmail.com>
 */
use yii\base\ErrorException;
use yii\web\AssetBundle;

class GoogleMapsAsset extends AssetBundle
{
	/**
	 * @var string Language ID supported by Google Maps API
	 */
	public $language = 'en';

	/**
	 * @var string Google Developers API key
	 * @link https://developers.google.com/maps/documentation/javascript/get-api-key
	 */
	public $apiKey = null;

	public function init(){
		parent::init();

		if($this->apiKey === null){
			throw new ErrorException("You must configure GoogleMapsAsset. See README.md for details.");
		}
		$this->js = [
			'https://maps.google.com/maps/api/js?key='.$this->apiKey.'&language='.$this->language,
		];
	}
} 