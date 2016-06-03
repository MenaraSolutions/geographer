# Geographer
[![Build Status](https://travis-ci.org/MenaraSolutions/geographer.svg)](https://travis-ci.org/MenaraSolutions/geographer)
[![Code Climate](https://codeclimate.com/github/MenaraSolutions/geographer/badges/gpa.svg)](https://codeclimate.com/github/MenaraSolutions/geographer/badges)
[![Test Coverage](https://codeclimate.com/github/MenaraSolutions/geographer/badges/coverage.svg)](https://codeclimate.com/github/MenaraSolutions/geographer/badges)
[![Total Downloads](https://poser.pugx.org/menarasolutions/geographer/downloads)](https://packagist.org/packages/menarasolutions/geographer)
[![Latest Stable Version](https://poser.pugx.org/MenaraSolutions/geographer/v/stable.svg)](https://packagist.org/packages/MenaraSolutions/geographer)
[![Latest Unstable Version](https://poser.pugx.org/MenaraSolutions/geographer/v/unstable.svg)](https://packagist.org/packages/MenaraSolutions/geographer)
[![License](https://poser.pugx.org/MenaraSolutions/geographer/license.svg)](https://packagist.org/packages/MenaraSolutions/geographer)

Geographer is a PHP library that knows how any country, state or city is called in any language.

Includes integrations with: Laravel 5, Lumen 5

## Dependencies

* PHP >= 5.6

## Installation via Composer

To install simply run:

```
composer require menarasolutions/geographer
```

Or add it to `composer.json` manually:

```json
{
    "require": {
        "menarasolutions/geographer": "~0.1"
    }
}
```

## Usage

```php
use MenaraSolutions\Geographer\Earth;
use MenaraSolutions\Geographer\Country;

// Default entry point is our beautiful planet
$earth = new Earth();

// Give me a list of all countries please
$earth->getCountries()->toArray();

// Oh, but please try to use short versions, eg. USA instead of United States of America
$earth->getCountries()->useShortNames()->toArray();

// Now please give me all states of Thailand
$countries = $earth->getCountries();
$thailand = $countries->findOne(['code' => 'TH']);
$thailand->getStates()->toArray();

// Oh, but I want them in Russian
$thailand->getStates()->setLanguage('ru')->toArray();

// Oh, but I want them inflicted to 'in' form (eg. 'in Spain')
$thailand->getStates()->setLanguage('ru')->inflict('in')->toArray();

// What's the capital and do you have a geonames ID for that? Or maybe latitude and longitude?
$capital = $thailand->getCapital();
$capital->getGeonamesId();
$capital->getLatitude();
$capital->getLongitude();
```

## Common methods

All objects can do the following:
```php
$object->toArray(); // Return a flat array with all data
$object->parent(); // Return a parent (city returns a state, state returns a country)
$object->getCode(); // Get default unique ID
$object->getShortName(); // Get short (colloquial) name of the object
$object->getLongName(); // Get longer name
```

## Earth API

Earth object got the following convenience methods:
```php
$earth->getAfrica(); // Get a collection of African countries
$earth->getEurope(); // Get a collection of European countries
$earth->getNorthAmerica(); // You can guess
$earth->getSouthAmerica(); 
$earth->getAsia();
$earth->getOceania();

$earth->getCountries(); // A collection of all countries
```

## Country API

Country objects got the following encapsulated data:
```php
$country->getCode(); // ISO 3611 2 character code
$country->getCode3(); // ISO 3611 3 character code
$country->getGeonamesCode(); // Geonames ID
$country->getArea(); // Area in square kilometers
$country->getCurrencyCode(); // National currency, eg. USD
$country->getPhonePrefix(); // Phone code, eg. 7 for Russia
$country->getPopulation(); // Population

$country->getStates(); // A collection of all states
```

## State API

At this moment Geographer only keeps cities with population above 50,000 for the sake of performance.

```php
$state->getCode(); // Either ISO 3166 (US, CH, BE and ME) or FIPS code
$state->getGeonamesCode(); // Geonames ID

$state->getCities(); // A collection of all cities
```

## City API

```php
$city->getCode(); // This is always a Geonames code for now
$city = City::build($id); // Instantiate a city directly, based on $id provided (Geonames) 
```

## Usage in Laravel 5

Good news is that Laravel will take care of singleton instance for you, so no matter how many times you call it – it's the same object.

```php
// Add in your config/app.php

'providers' => [
    '...',
    'MenaraSolutions\Geographer\Integrations\LaravelServiceProvider',
];

'aliases' => [
    '...',
    'Geographer'    => 'MenaraSolutions\Geographer\Integrations\LaravelFacade',
];

// Start playing with it, all the same calls
Geographer::getCountries()->useShortNames()->toArray();
```

## Current coverage

| Language  |  Countries   |   States   |    Cities    |
|-----------|--------------|------------|--------------|
| English   | ✓            | ✓         | ✓            |
| Russian   | ✓            | ✓         | ✓            |
| Spanish   | ✓            | ✓         | ✓           |            
| Italian   | ✓            | ✓         | ✓           |
| French    | ✓            | ✓         | ✓           |
| Chinese Mandarin | ✓            | ✓         | ✓           |
| Bahasa Indonesia | WIP   | WIP        | WIP          |

## Todo

1. Add Memcached repository driver
2. Add translated cities
3. Add unit tests (in addition to existing integration tests)

## Projects using Geographer

* [Boogie Call – Music event finder](https://www.boogiecall.com)
* [Rapport – Real-time random photo and video streams](https://www.rapport.fm)

Tell us about yours!

## License

The MIT License (MIT)
Copyright (c) 2016 Denis Mysenko

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
