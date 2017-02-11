<?php

namespace Tests\Languages;

use Tests\Test;
use MenaraSolutions\Geographer\Earth;

class RussianTest extends Test
{
    protected $forms = ['', 'to', 'from'];

    /**
     * @var string
     */
    protected $languageCode = 'ru';

    /**
     * @var int
     */
    protected $threshold = 50;

    protected $translit = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'x',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'shh',
        'ь' => '\'',
        'ы' => 'y',
        'ъ' => '\'\'',
        'э' => 'e\'',
        'ю' => 'yu',
        'я' => 'ya',
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'YO',
        'Ж' => 'Zh',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'X',
        'Ц' => 'C',
        'Ч' => 'CH',
        'Ш' => 'SH',
        'Щ' => 'SHH',
        'Ь' => '\'',
        'Ы' => 'Y\'',
        'Ъ' => '\'\'',
        'Э' => 'E\'',
        'Ю' => 'YU',
        'Я' => 'YA',
    ];

    /**
     * @test
     */
    public function all_countries_have_translated_names()
    {
        $earth = new Earth();
        $countries = $earth->getCountries();

        foreach($countries as $country) {
            $this->assertNotEquals($country->setLocale($this->languageCode)->getName(), $country->setLocale('en')->getName());
            //echo $country->inflict('from')->setLocale($this->languageCode)->getName() . "\n";
        }
    }

    /**
     * @test
     */
    public function calculate_state_translation_coverage()
    {
        $planet = (new Earth())->setLocale('ru')->setStandard('iso');

        $total = 0;
        $translated = 0;

        foreach ($planet->getCountries() as $country) {
            $states = $country->getStates();

            foreach ($states as $state) {
                $total++;

                if (preg_match('/[A-Za-z]+/', $state->inflict('default')->setLocale('ru')->getName())) {
                } else {
                    $translated++;
                }
            }

        }

        echo "Russian translation coverage for ISO states: " . round(($translated / $total) * 100) . "%\n";
    }

    /**
     * @test
     */
    public function all_iso_states_have_russian_translations()
    {
        $planet = (new Earth())->setLocale('ru')->setStandard('iso');

        // Parse translations
        $input = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/ru.txt');
        $line = strtok($input, "\r\n");
        $translations = [];

        while ($line !== false) {
            list($code, $translation, $garbage) = explode("\t", $line);
            if (stripos($translation, '.svg') !== false) {
                $translation = substr($translation, stripos($translation, '.svg') + 5);
            }
            if (stripos($translation, '.png') !== false) {
                $translation = substr($translation, stripos($translation, '.png') + 5);
            }
            $translations[$code] = $translation;
            $line = strtok("\r\n");
        }


        $array = [];
        $output = [
            'code' => "0",
            'long' => [
                'default' => ''
            ]
        ];

        foreach ($planet->getCountries() as $country) {
            $states = $country->getStates();

            $hasFailures = false;

            $total = 0;
            $translated = 0;

            foreach ($states as $state) {
                $total++;
                if (preg_match('/[A-Za-z]+/', $state->inflict('default')->setLocale('ru')->getName())) {
                    $output['code'] = strval($state->getIsoCode());

                    if (! empty($translations[strval($state->getIsoCode())])) {
                        $output['long']['default'] = $translations[strval($state->getIsoCode())];
                    } else {
                        $output['long']['default'] = strtr($state->getName(), array_flip($this->translit)) . ' *';
                    }

                    $array[] = $output;
                    $hasFailures = true;
                } else {
                    $translated++;
                }
            }

            if ($hasFailures) {
                echo "Got " . $translated . " out of " . $total . " translations\n";
                break;
            }
        }

        //echo json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     *
     */
    public function specific_country_has_all_states()
    {
        $country = (new Earth())->findOneByCode('TT')->setLocale('ru');
        $states = $country->getStates();

        $array = [];
        $output = [
            'code' => "0",
            'long' => [
                'default' => ''
            ]
        ];

        foreach ($states as $state) {
            $cities = $state->getCities();

            echo "id: " . $state->getCode() . " iso: " . $state->getIsoCode() .  " names: "
                . $state->inflict('default')->getName() . "  "
                . $state->inflict('in')->getName() . "  "
                . $state->inflict('from')->getName() . "\n";

            foreach ($cities as $city) {
                echo $city->name;
            }

            if (preg_match('/[A-Za-z]+/', $state->inflict('default')->getName())) {
                $output['code'] = strval($state->getCode());
                $output['long']['default'] = $state->getIsoCode();
                $array[] = $output;
            }
        }

        echo json_encode($array);
    }
}
