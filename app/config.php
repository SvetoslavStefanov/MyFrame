<?php

return array(
	'baseurl'		=> dirname(dirname(dirname(__FILE__))),//dirname($_SERVER['SCRIPT_NAME']),
	'publicDir'		=> 'public_html',
	'database'		=> array(
		'host'		=> 'localhost',
		'user'		=> 'root',
		'password'	=> '',
		'database'	=> 'framework'
	),
        'site_url'               => 'http://localhost/public_html',
    );