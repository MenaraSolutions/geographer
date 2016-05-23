<?php

$file = fopen("tmp.txt", "r");

while(!feof($file)) {
	$buf = fgets($file, 255);
	if(empty($buf)) continue;
	$fields = explode("\t", $buf);
	echo json_encode([
		'iso-3611' => [$fields[1], $fields[2]],
		'languages' => [],
		'names' => [
			'long' => $fields[0],
			'short' => ''
		]
	], JSON_PRETTY_PRINT);
	echo ",\n";
}
