# yii2-google-maps-api

The main purpose of this extension is finding coordinates by address from Google Maps API right from Yii2 application.
It also providing asset to use Google API Javascript library in your views. 

Installation
------------
Add a dependency to your project's composer.json:

```json
{
	"require": {
		"kossmoss/yii2-google-maps-api": "^0.2"
	}
}
```

## Usage

This extension allows to Google Maps API JavaScript library in your views.
GoogleMapsAsset will attach library to view only if you have properly configured asset params:

```php
	'components' => [
		...
		'assetManager' => [
			'bundles' => [
				'kossmoss\GoogleMaps\GoogleMapsAsset' => [
					'apiKey' => 'YOUR_API_KEY_HERE',   // get at https://developers.google.com/maps/documentation/javascript/get-api-key
					'language' => 'ru', // use language code supported by Google Maps API  (default: en)
				],
			],
		],
		...
	],
```

Then register asset in your view and you'll be able to use Google Maps API JS library:

```php
<?php
/* @var $this yii\web\View */
\kossmoss\GoogleMaps\GoogleMapsAsset::register($this);
?>
<div id="map" style="width: 400px; height: 300px;"></div>

<?php
	$this->registerJs('
		var map;
		initMap();
		
		function initMap() {
			map = new google.maps.Map(document.getElementById("map"), {
				center: {lat: -34.397, lng: 150.644},
				zoom: 8
			});
		}
	');
```

This extension also includes php-google-map-api library from https://github.com/streetlogics/php-google-map-api
Many years have gone since it was build and for a moment it has many things to refactor,
but for now maybe you find useful it's synchronous calls to Google MAP API like this:

```php
use kossmoss\GoogleMaps\GoogleMaps;

...

$coords = GoogleMaps::coordsByAddress("Lisboa, Portugal");
```

If you want to use other features from original extension, you need to use GoogleMapAPI class instead of GoogleMaps class.
Here you can find some documentation for GoogleMapsAPI class:

-  [Demos for php-google-map-api](http://www.bradwedell.com/php-google-maps-api/demos/)
-  [Google Maps V3 Documentation](http://code.google.com/apis/maps/documentation/v3/)
