<?php

$countries = json_decode(file_get_contents("countries.json"), true);

$translations = array();

foreach ($countries as $country) {
	$translations[] = [
		'code' => $country['iso_3611'][0],
		'long' => [
			'default' => $country['names']['long'],
			'in' => null,
			'from' => null
		],
		'short' => [
                        'default' => $country['names']['short'],
                        'in' => null,
                        'from' => null
		]
 	];
}

file_put_contents("translations/country/ru.json", json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
