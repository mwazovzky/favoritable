<?php

try {
	$myFile = json_decode(file_get_contents('composer.json'), true);

	$myFile['autoload']['psr-4']["Mikewazovzky\\Favoritable\\"] = 'vendor/Mikewazovzky/Favoritable/src/';
	$myFile['autoload-dev']['classmap'] = [ 'vendor/Mikewazovzky/Favoritable/tests' ];
	$myFile['autoload-dev']['files'] = [ 'tests/functions.php' ];
	
	file_put_contents('composer.json', json_encode($myFile, JSON_PRETTY_PRINT)); // JSON_UNESCAPED_SLASHES));

} catch (Exception $e) {
	echo 'Failed to modify composer.json. Error message: ' . $e->getMessage();
}

echo 'composer.json autoload sections has neen updated.';

