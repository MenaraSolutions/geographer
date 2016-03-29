# Fluent Geonames

A PHP library that knows how any country, state or city is called in another language.

Includes integrations with: Laravel 5

## Dependencies

* PHP >= 5.5

## Installation via Composer

To install simply run:

```
composer require menarasolutions/fluent-geonames
```

Or add it to `composer.json` manually:

```json
{
    "require": {
        "menarasolutions/fluent-geonames": "~1.2"
    }
}
```

## Usage

```php
use MenaraSolutions\FluentGeonames\Planet;
use MenaraSolutions\FluentGeonames\Country;

$planet = new Planet();

// Give me a list of all countries please
$planet->getCountries()->toArray();

// Oh, but please try to use short versions, eg. USA instead of United States of America
$planet->getCountries()->useShortNames()->toArray();

// Now please give me all states of Thailand
$countries = $planet->getCountries();
$thailand = $countries->find(['iso_code' => 'th']);
$thailand->getStates()->toArray();

// Oh, but I want them in Russian
$thailand->getStates()->setLanguage('ru')->toArray();

// Oh, but I want them conjugated to 'in' form
$thailand->getStates()->setLanguage('ru')->conjugateIn()->toArray();

// What's the capital and do you have a geonames ID for that? Or maybe latitude and longitude?
$capital = $thailand->getCapital();
$capital->getGeonamesId();
$capital->getLatitude();
$capital->getLongitude();
```

## Todo

## License

The MIT License (MIT)
Copyright (c) 2016 Denis Mysenko

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
