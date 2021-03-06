<?php
// DO NOT EDIT THIS FILE!
// Your modifications go to config.local.inc.php4!
$version			= '0.2.0';

// You may want to move some files to a second webserver
// in order to decrease the load on your SSL-enabled one.
// (Directories out of the client's browser's view.)
$cfg['images_dir']	= 'static/images';	// This is where you store all the images
$cfg['design_dir']	= 'static/design';	// CSS
$cfg['upload_dir']	= 'static/uploads';	// such as attachments
$cfg['sample_msg']	= './static/testdata';	// sample emails
// This shall be the URL of this server, not the possible "other one":
$cfg['AbsoluteUri']	= '';			// i.e.: http://lists.example.com/oml

// Specify here how date shall be formatted by default. See function 'date' for possible values.
$cfg['display']['date_format']	= 'Y-m-d H:i';

// How many entries shall we display in RSS?
$cfg['rss']['num_messages']		= 15;
$cfg['rss']['max_description_length']	= 256;
$cfg['rss']['min_age']			= 10;		// in minutes

// Select one of: plain
$cfg['theme']		= 'plain';

// Copy this line and change it according to your environment.
/*
$cfg['DB'] = array(
	'TYPE'	=> 'mysql',
	'HOST'	=> 'localhost',
	'USER'	=> '##MysqlUser##',
	'PASS'	=> '##MysqlSecret##',
	'DB'	=> '##MysqlDB##',
	'PREFIX'	=> ''			// prefix to table names
);
*/

?>