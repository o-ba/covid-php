# CovidPHP

CovidPHP is a PHP API wrapper for the [Coronavirus tracker API](https://github.com/ExpDev07/coronavirus-tracker-api).

Per default the data is provided by the [Johns Hopkins University Center for Systems Science and Engineering (JHU CSSE)](https://github.com/CSSEGISandData/COVID-19), but you can also use [csbs](https://www.csbs.org/information-covid-19-coronavirus) as source.

## Installation

You can install ``o-ba/covid-php`` using Composer:

```
composer require o-ba/covid-php
```

Require the composer autoloader in your script:

```
require 'vendor/autoload.php';
```

## Usage

Create a instance of the API wrapper:
```
$covidApi = new \Bo\CovidPHP\CovidApi();
```

Get the latest global amount of total confirmed cases, deaths and recoveries:
```
$covidApi->getLatest();
```

Get all locations:
```
$covidApi->getAllLocations();
```

Get location data by country code:
```
$covidApi->findByCountryCode('DE'); // Returns location data for germany
```

Get location data for a specific location:
```
$covidApi->findByLocation(11); // Returns location data for germany
```

## Note
- Setting ``$includeTimelines`` adds timeline data to the response
- Setting ``$source`` let's you specify which source the data should be fetched from (default: ``jhu``)
- All responses will be decoded and returned as ``array``.

## License

[MIT License](http://opensource.org/licenses/MIT)