<?php

$file = fopen("admin1CodesASCII.txt", "r");

$states = [];

while(!feof($file)) {
	$line = trim(fgets($file, 255));
	if (empty($line)) continue;
	list($code, $name, $ascii, $geoid) = explode("\t", $line);
	list($countryCode, $stateCode) = explode(".", $code);

	if (!isset(${$countryCode})) ${$countryCode} = [];
	$codeType = (in_array($countryCode, ['US', 'CH', 'BE', 'ME'])) ? 'iso_3166' : 'fips';

	$state = [];
	//$state['iso_3611'] = $countryCode;
	$state['ids'] = [
		$codeType => $stateCode,
		'geonames' => intval($geoid)
	];
	$state['names'] = [
		'long' => !empty($name) ? $name : $ascii,
		'short' => null
	];

	${$countryCode}[] = $state;
	$states[$countryCode] = ${$countryCode};
}

foreach($states as $countryCode => $country) {
	file_put_contents("states/" . $countryCode . ".json", json_encode($country, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

fclose($file);
