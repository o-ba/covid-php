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

Create a instance of the API wrapper first:
```
$covidApi = new \Bo\CovidPHP\CovidApi();
```

### Examples

Get all available sources:
```
$covidApi->getSources();
```

Get the latest global amount of total confirmed cases, deaths and recoveries:
```
$covidApi->getLatest();
```

Get all locations:
```
$covidApi->getAllLocations();
```

Get all locations including timelines:
```
$covidApi->getAllLocations(true);
```

Get location data by country code:
```
$covidApi->findByCountryCode('DE');
```

Get location data by country code from ``csbs`` as source:
```
$covidApi->findByCountryCode('US', false, 'csbs');
```

Get location data for a specific location:
```
$covidApi->findByLocation(11);
```

## Note
- Setting ``$includeTimelines`` adds timeline data to the response
- Setting ``$source`` let's you specify which source the data should be fetched from (default: ``jhu``)
- The mentioned parameters are available for all methods except ``CovidApi::getSources()`` and ``CovidApi::getLatest()``
- All responses will be decoded and returned as ``array``

## License

[MIT License](http://opensource.org/licenses/MIT)