# Geographer
[![Build Status](https://travis-ci.org/MenaraSolutions/geographer.svg)](https://travis-ci.org/MenaraSolutions/geographer)
[![Code Climate](https://codeclimate.com/github/MenaraSolutions/geographer/badges/gpa.svg)](https://codeclimate.com/github/MenaraSolutions/geographer/badges)
[![Test Coverage](https://codeclimate.com/github/MenaraSolutions/geographer/badges/coverage.svg)](https://codeclimate.com/github/MenaraSolutions/geographer/badges)
[![Total Downloads](https://poser.pugx.org/menarasolutions/geographer/downloads)](https://packagist.org/packages/menarasolutions/geographer)
[![Latest Stable Version](https://poser.pugx.org/MenaraSolutions/geographer/v/stable.svg)](https://packagist.org/packages/MenaraSolutions/geographer)
[![Latest Unstable Version](https://poser.pugx.org/MenaraSolutions/geographer/v/unstable.svg)](https://packagist.org/packages/MenaraSolutions/geographer)
[![License](https://poser.pugx.org/MenaraSolutions/geographer/license.svg)](https://packagist.org/packages/MenaraSolutions/geographer)

Geographer is a PHP library that knows how any country, state or city is called in any language. [Documentation on the official website](https://geographer.su/documentation/php/)

Includes integrations with: Laravel 5, Lumen 5

![Geographer](https://www.mysenko.com/images/geographer_cover2.jpg)

## Dependencies

* PHP >= 5.5

## Installation via Composer

To install simply run:

```
$ composer require menarasolutions/geographer
```

Or add it to `composer.json` manually:

```json
{
    "require": {
        "menarasolutions/geographer": "~0.3"
    }
}
```

This, main package is shipped with English language so add extra dependencies for your
other languages, eg.:

```
$ composer require menarasolutions/geographer-es
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
$thailand = $earth->getCountries()->findOne(['code' => 'TH']); // You can call find on collection
$thailand = $earth->findOne(['code' => 'TH']); // Or right away on division
$thailand = $earth->findOneByCode('TH'); // Alternative shorter syntax
$thailand = Country::build('TH'); // You can build a country object directly, too
$thailand->getStates()->toArray();

// Oh, but I want them in Russian
$thailand->getStates()->setLocale('ru')->toArray();

// Oh, but I want them inflicted to 'in' form (eg. 'in Spain')
$thailand->getStates()->setLocale('ru')->inflict('in')->toArray();

// Or if you prefer constants for the sake of IDE auto-complete
$thailand->getStates()->setLocale(TranslationAgency::LANG_RUSSIAN)->inflict(TranslationAgency::FORM_IN)->toArray();

// What's the capital and do you have a geonames ID for that? Or maybe latitude and longitude?
$capital = $thailand->getCapital();
$capital->getGeonamesCode();
$capital->getLatitude();
$capital->getLongitude();
```

## Collections

Arrays of administrative divisions (countries, states or cities) are returned as collections – a modern
way of implementing arrays. Some of the available methods are:

```php
$states->sortBy('name'); // States will be sorted by name
$states->setLocale('ru')->sortBy('name'); // States will be sorted by Russian translations/names
$states->find(['code' => 472039]); // Find 1+ divisions that match specified parameters 
$states->findOne(['code' => 472039]); // Return the first match only
$states->findOneByCode(472039); // Convenience magic method
$states->toArray(); // Return a flat array of states
$states->pluck('name'); // Return a flat array of state names
```

## Common methods on division objects

All objects can do the following:
```php
$object->toArray(); // Return a flat array with all data
$object->parent(); // Return a parent (city returns a state, state returns a country)
$object->getCode(); // Get default unique ID
$object->getShortName(); // Get short (colloquial) name of the object
$object->getLongName(); // Get longer name
$object->getCodes(); // Get a plain array of all available unique codes
```

You can access information in a number of ways, do whatever you are comfortable with:
```php
$object->getName(); // Get object's name (inflicted and shortened when necessary)
$object->name; // Same effect
$object['name']; // Same effect
$object->toArray()['name']; // Same effect again
```

## Subdivision standards

By default, we will use ISO-3166-1 country and ISO 3166-2 state classification. Therefore, countries or states that don't have ISO codes are not visible by default.
Please note that FIPS 10-4 is a deprecated (abandoned) standard. It's better not to rely on it – new states and/or countries won't appear in FIPS.

You can change subdivision standard with ```setStandard``` method:

```php
$country->setStandard(DefaultManager::STANDARD_ISO); // ISO subdivisions
$country->setStandard(DefaultManager::STANDARD_FIPS); // FIPS 10-4 subdivisions
$country->setStandard(DefaultManager::STANDARD_GEONAMES); // Geonames subdivisions
```

This will affect ```getStates()``` and ```getCountries()``` output.

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
$earth->withoutMicro(); // Only countries that have population of at least 100,000
```

By default, we will use ISO 3166-1 country classification.

## Country API

Country objects got the following encapsulated data:
```php
$country->getCode(); //ISO 3166-1 alpha-2 (2 character) code
$country->getCode3(); // ISO 3166-1 alpha-3
$country->getNumericCode(); // ISO 3166-1 numeric code
$country->getGeonamesCode(); // Geonames ID
$country->getFipsCode(); // FIPS code
$country->getArea(); // Area in square kilometers
$country->getCurrencyCode(); // National currency, eg. USD
$country->getPhonePrefix(); // Phone code, eg. 7 for Russia
$country->getPopulation(); // Population
$country->getLanguage(); // Country's first official language

$country->getStates(); // A collection of all states
Country::build('TH'); // Build a country object based on ISO code
```

Geonames, ISO 3166-1 alpha-2, alpha-3 and numeric codes are four viable options to reference country in your data store.

## State API

At this moment Geographer only keeps cities with population above 50,000 for the sake of performance.

```php
$state->getCode(); // Get default code (currently Geonames)
$state->getIsoCode(); // Get ISO 3166-2 code  
$state->getFipsCode(); // Get FIPS code
$state->getGeonamesCode(); // Get Geonames code

$state->getCities(); // A collection of all cities
$state = State::build($id); // Instantiate a state directly, based on $id provided (Geonames or ISO)
```

Geonames, ISO 3166-2 and FIPS are all unique codes so all three can be used to reference states in your data store.

## City API

```php
$city->getCode(); // This is always a Geonames code for now
$city = City::build($id); // Instantiate a city directly, based on $id provided (Geonames) 
$city->getLatitude(); // City's latitude
$city->getLongitude(); // City's longitude
$city->getPopulation(); // Population
```

Geonames ID is currently the only viable option to reference a city in your data store. 

## Integrations with frameworks

[Official Laravel package](https://github.com/MenaraSolutions/geographer-laravel)

## Current coverage: subdivisions

| Type | ISO 3166 | FIPS | Geonames | GENC |
|------|----------|------|----------|------|
| Countries | 100% | Coming soon | 100% | TBC |
| States | 100% | Coming soon | 100% | TBC |

Subdivision data is kept in a separate repo - [geographer-data](https://github.com/MenaraSolutions/geographer-data) so that it 
may be reused by different language SDKs. 

## Current coverage: translations

By default Geographer assumes that you use Packagist (Composer) to install language packages, therefore
we will expect them in vendor/ folder. There is no need to manually turn on an extra language, but if you
attempt to use a non-existing language – expect an exception.

| Language  |  Countries   |   States   |    Cities    | Package |
|-----------|--------------|------------|--------------|---------|
| English   | 100%         | 100%       | 100%         | [geographer-data](https://github.com/MenaraSolutions/geographer-data) |
| Russian   | 100%         | 100%       | 63%          | [geographer-ru](https://github.com/MenaraSolutions/geographer-ru) | 
| Ukrainian | ✓            | ✓         | ✓           | [geographer-uk](https://github.com/MenaraSolutions/geographer-uk) |           
| Spanish   | ✓            | ✓         | ✓           | [geographer-es](https://github.com/MenaraSolutions/geographer-es) |           
| Italian   | ✓            | ✓         | ✓           | [geographer-it](https://github.com/MenaraSolutions/geographer-it) |
| French    | ✓            | ✓         | ✓           | [geographer-fr](https://github.com/MenaraSolutions/geographer-fr) |
| German    | ✓            | ✓         | ✓           | [geographer-de](https://github.com/MenaraSolutions/geographer-de) |
| Chinese Mandarin | ✓            | ✓         | ✓           | [geographer-zh](https://github.com/MenaraSolutions/geographer-zh) |

English texts are included in the data package and are used as default metadata. 

## Vision

Our main principles and goals are:

1. Be lightweight and independent – so that this package can be pulled anywhere alone
2. Coverage – Geographer should cover all countries and languages
3. Be extensible – developers should be able to override and extend easily

## Performance

While not a number one priority at this stage, we will try maintain reasonable CPU and memory performance. Some benchmarks:

**Inflating a city based on its Id**

Time: 6 ms, memory: 81056 bytes

## Todo

1. Add a basic spatial index
2. Add some unit tests (in addition to existing integration tests)
3. Add coverage information for language packages

## Projects using Geographer

* [Boogie Call – Music event finder](https://www.boogiecall.com)
* [Rapport – Real-time random photo and video streams](https://www.rapport.fm)

Tell us about yours!

## Contribution

Read our [Contribution guide](https://github.com/MenaraSolutions/geographer/blob/master/CONTRIBUTING.md)

## License

The MIT License (MIT)
Copyright (c) 2016 Denis Mysenko

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
