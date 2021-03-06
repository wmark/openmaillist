#!/bin/env php
<?php
if(isset($_SERVER['SERVER_SOFTWARE'])) {
	header('HTTP/1.1 303 See Other');
	header('Location: index.php');
	die('This is an executable, not suited for being served by any webserver.');
}

if($argc <= 1) {
	die(<<<TXT
Usage:		| piper.php LISTNAME

Will try to store the given message in openmaillist.
The message in question has to be provided through STDIN.

TXT
	);
}
////////////////////////////////////////////////////////////////////////////////
$former_directory	= getcwd();
chdir(dirname($_ENV['_']));

include('./inc/_prepend.php');

////////////////////////////////////////////////////////////////////////////////

$input = fread(STDIN, 64000);

$myEmail = new oml_email($input);

if(!$myEmail->is_administrative()) {
	try {
		$theList	= $superior->get_list_by_name($argv[1]);
		$oml->put_email($theList, $myEmail);
	} catch(Exception $e) {
		die($e->getMessage()."\n");
	}
}

chdir($former_directory);
include('./inc/_append.php');

?>