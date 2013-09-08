<?php
// set strict mode
error_reporting(E_ALL | E_STRICT);
//error_reporting(0);

//set display errors to on
ini_set('display_errors','on');

// set default date time zone ( http://bg.php.net/manual/en/timezones.php )
date_default_timezone_set('Europe/Sofia');

// set utf-8 encoding
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');

// directory constants
define('ROOT_DIR',			realpath(dirname(__FILE__)));
define('LIB_DIR',			ROOT_DIR . '/lib');
define('VIEWS_DIR',			ROOT_DIR . '/views');
define('MODELS_DIR',		ROOT_DIR . '/models');
define('CONTROLLERS_DIR',	ROOT_DIR . '/controllers');
define('ADMIN_DIR',                         'admin');
define('PLUGINS_DIR',                       ROOT_DIR.'/Plugins');
define('COMPONENTS_DIR',                    ROOT_DIR.'/Components');

$config = include ROOT_DIR . '/config.php';

// constansts from the config
define('ENVIROMENT', 	'development');
define("PUBLIC_DIR", $config['publicDir']);
define('PUBLIC_BASE_DIR', 	realpath(ROOT_DIR . '/../' . $config['publicDir']));
define('BASEURL',		$config['baseurl']);
define('SITE_URL',                          $config['site_url']);
// load basic libs
require LIB_DIR . '/AutoLoader.php';
require LIB_DIR . '/functions.php';
require LIB_DIR . '/Formbuilder.php';
require LIB_DIR . '/Plugin.php';


set_exception_handler('handle_exception');

// register autoloader
AutoLoader::register(array(LIB_DIR, MODELS_DIR, CONTROLLERS_DIR, PLUGINS_DIR, ADMIN_DIR));

// connect active record with database
ActiveRecord::$db = new SqlHandler($config['database']);
// clear the config value
unset($config);
setlocale(LC_ALL, 'bg_BG.UTF-8');
session_start();
