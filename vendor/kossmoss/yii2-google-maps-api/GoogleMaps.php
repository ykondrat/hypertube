<?php

namespace kossmoss\GoogleMaps;

/**
 * Component for using Yii2 with GoogleMapAPI https://github.com/streetlogics/php-google-map-api
 *
 * @copyright 2016 Konstantin Petrov
 * @author kossmoss radiokoss@gmail.com
 * @date: 10.02.2016
 */
use \Yii;
use yii\base\ErrorException;
use kossmoss\GoogleMaps\GoogleMapAPI;

class GoogleMaps
{
	/**
	 * @var GoogleMapAPI
	 */
	private static $_google;

	/**
	 * @var integer Timestamp of last query to Google API
	 */
	private static $_lastGoogleSearchTime = 0;

	/**
	 * @var integer Timeout duration between subsequent queries to Google API (seconds)
	 */
	private static $_googleTimeout = 2;

	/**
	 * Sets specified timeout
	 * @param integer $timeout
	 */
	public static function  setGoogleTimeout($timeout = 2)
	{
		self::$_googleTimeout = $timeout;
	}

	/**
	 * Returns current timeout
	 * @return int
	 */
	public static function  getGoogleTimeout()
	{
		return self::$_googleTimeout;
	}

	/**
	 * Returns GoogleMaps instance to work with
	 *
	 * @param mixed $options Map options array
	 * @return GoogleMapAPI
	 */
	public static function map($options = null)
	{
		if (empty(self::$_google)) {
			self::$_google = new GoogleMapAPI();
			self::$_google->_minify_js = false;

			// Apply options
			if (is_array($options)) {
				foreach ($options as $key => $value) {
					self::$_google->$key = $value;
				}
			};
		}

		return self::$_google;
	}

	/**
	 * Search coordinates by address
	 *
	 * @param string $searchString search query with address
	 * @return array|bool
	 */
	public static function coordsByAddress($searchString)
	{
		$result = false;
		try {
			// we must be sure we don't send queries too frequently to avoid be banned by Google Maps API
			if (self::$_googleTimeout != 0) {
				$timeToWait = self::$_lastGoogleSearchTime + self::$_googleTimeout - time();
				if ($timeToWait > 0) {
					sleep($timeToWait);
				}
			}

			$searchResult = self::map()->geoGetCoordsFull($searchString);
			self::$_lastGoogleSearchTime = time();

			if ($searchResult && $searchResult->status == 'OK') {
				$result = [
					'latitude' => $searchResult->results[0]->geometry->location->lat,
					'longitude' => $searchResult->results[0]->geometry->location->lng,
				];
			}
		} catch (ErrorException $e) {
			\Yii::error('Can\'t load coordinates from Google Maps API: ' . $e->getMessage());
		}

		return $result;
	}
}
