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
$thailand = $countries->find(['code' => 'TH']);
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

## Todo

1. Add memcached support
2. Add Laravel and Lumen integrations (service providers)
3. Add Russian and Spanish versions for all countries, states and major cities

## License

The MIT License (MIT)
Copyright (c) 2016 Denis Mysenko

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
