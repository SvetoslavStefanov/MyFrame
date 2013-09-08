<?php

class FormValidator
{

    public static $errors = array();
    private static $message;
    private static $required = false;
    protected static $name = null;

    public static function validate ($value, $field, $rules)
    {
        if (!is_array($rules))
            return false;

        if (isset($rules['required']) && $rules['required'] == 1) {
            self::$required = true;
            unset($rules['required']);
            $rules['required'] = 1;
        }

        if (!self::$required && empty($value)) {
            self::$required = false;
            return true;
        }

        foreach ($rules as $condition => $rule) {
            if (!self::$condition($value, $rule)) {
                self::addError($field, self::$message);
            }
        }

        self::$required = false;
        return self::$errors ? false : true;
    }

    public static function setName ($name)
    {
        self::$name = $name;
    }

    public static function getName ()
    {
        return self::$name;
    }

    /*
     * @param: value - the value from input field
     */

    public static function captcha ($value)
    {
        self::$message = 'The code from captcha isn\'t correct';
        if (!isset($_SESSION['captcha']))
            return false;

        if ($_SESSION['captcha'] != md5($value . 'mys3cr3t\/\/0rD' . $value))
            return false;
        return true;
    }

    public static function isCoordinate ($value)
    {
        self::$message = "Please enter real coordinates";
        return (bool) preg_match("/^[-0-9]+\\.[0-9]+$/", $value);
    }

    /**
     * Check if given string is valid url
     *
     * @param	string		$string		string for checking
     * @return	boolean					is the given $string is valid url address
     */
    public static function isUrl ($string)
    {
        self::$message = "URL ??????? ?? ? ???????";

        //$pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
        //return (bool) preg_match($pattern, $string);
        //return (bool) preg_match('|[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $string);
        if (substr($string, 0, 4) != "http")
            $string = 'http://' . $string;
        // parse url
        $url = parse_url($string);

        // check if url could be passed
        if (!$url)
            return false;

        // check if scheme is given
        if (empty($url['scheme']) || !in_array($url['scheme'], array('http', 'https', 'ftp', 'file')))
            return false;

        // validate subdomain.domain.tld
        $parts = explode('.', strtolower($url['host']));

        if (count($parts) == 2) {
            $parts[2] = 'www';
            $parts = array_reverse($parts);
            $test = $parts[1];
            $parts[1] = $parts[2];
            $parts[2] = $test;
        }

        if (count($parts) < 2)
            return false;

        list($subdomain, $domain, $tld) = $parts;

        // just check if subdomain and domain are setted
        if (!$subdomain || !$domain)
            return false;

        // @note ftp://data.iana.org/TLD/tlds-alpha-by-domain.txt  List of all TLDs by domain
        if (!in_array($tld, array(
                    'ac', 'ad', 'ae', 'aero', 'af', 'ag', 'ai', 'al', 'am', 'an', 'ao',
                    'aq', 'ar', 'arpa', 'as', 'at', 'au', 'aw', 'ax', 'az', 'ba', 'bb',
                    'bd', 'be', 'bf', 'bg', 'bh', 'bi', 'biz', 'bj', 'bm', 'bn', 'bo',
                    'br', 'bs', 'bt', 'bv', 'bw', 'by', 'bz', 'ca', 'cat', 'cc', 'cd',
                    'cf', 'cg', 'ch', 'ci', 'ck', 'cl', 'cm', 'cn', 'co', 'com', 'coop',
                    'cr', 'cu', 'cv', 'cx', 'cy', 'cz', 'de', 'dj', 'dk', 'dm', 'do',
                    'dz', 'ec', 'edu', 'ee', 'eg', 'er', 'es', 'et', 'eu', 'fi', 'fj',
                    'fk', 'fm', 'fo', 'fr', 'ga', 'gb', 'gd', 'ge', 'gf', 'gg', 'gh',
                    'gi', 'gl', 'gm', 'gn', 'gov', 'gp', 'gq', 'gr', 'gs', 'gt', 'gu',
                    'gw', 'gy', 'hk', 'hm', 'hn', 'hr', 'ht', 'hu', 'id', 'ie', 'il',
                    'im', 'in', 'info', 'int', 'io', 'iq', 'ir', 'is', 'it', 'je', 'jm',
                    'jo', 'jobs', 'jp', 'ke', 'kg', 'kh', 'ki', 'km', 'kn', 'kr', 'kw',
                    'ky', 'kz', 'la', 'lb', 'lc', 'li', 'lk', 'lr', 'ls', 'lt', 'lu',
                    'lv', 'ly', 'ma', 'mc', 'md', 'mg', 'mh', 'mil', 'mk', 'ml', 'mm',
                    'mn', 'mo', 'mobi', 'mp', 'mq', 'mr', 'ms', 'mt', 'mu', 'museum', 'mv',
                    'mw', 'mx', 'my', 'mz', 'na', 'name', 'nc', 'ne', 'net', 'nf', 'ng',
                    'ni', 'nl', 'no', 'np', 'nr', 'nu', 'nz', 'om', 'org', 'pa', 'pe',
                    'pf', 'pg', 'ph', 'pk', 'pl', 'pm', 'pn', 'pr', 'pro', 'ps', 'pt',
                    'pw', 'py', 'qa', 're', 'ro', 'ru', 'rw', 'sa', 'sb', 'sc', 'sd',
                    'se', 'sg', 'sh', 'si', 'sj', 'sk', 'sl', 'sm', 'sn', 'so', 'sr',
                    'st', 'su', 'sv', 'sy', 'sz', 'tc', 'td', 'tf', 'tg', 'th', 'tj',
                    'tk', 'tl', 'tm', 'tn', 'to', 'tp', 'tr', 'travel', 'tt', 'tv', 'tw',
                    'tz', 'ua', 'ug', 'uk', 'um', 'us', 'uy', 'uz', 'va', 'vc', 've',
                    'vg', 'vi', 'vn', 'vu', 'wf', 'ws', 'ye', 'yt', 'yu', 'za', 'zm',
                    'zw')))
            return false;

        return true;
    }

    /**
     * Check if given string is phone number or not
     *
     * @param	string		$string		string to check
     * @return	boolean					check result
     *
     * @matches
     * 	->	800-555-1212
     * 	->	800 555 1212
     * 	->	800.555.1212
     * 	->	(800) 555-1212
     * 	->	1-800-555-1212
     * 	->	800-555-1212-1234
     * 	->	800-555-1212x1234
     * 	->	800-555-1212 ext. 1234
     * 	->	work 1-(800) 555.1212 #1234
     */
    public static function isPhone ($string)
    {
        self::$message = "Invalid String";
        // @note: for extracting data use: /(\d{2,3})\D.*(\d{1,3})\D.*(\d{2,4})?(\D*(\d*))$/
        return !(boolean) preg_match('/\d{2,3}\D.*\d{1,3}\D.*\d{2,4}?(\D*\d*)$/', $string);
    }

    protected static function address ($value, $rule)
    {
        self::$message = "Invalid String";

        return (bool) preg_match("/^[a-zA-Z0-9]$/", $value);
    }

    protected static function min_length ($value, $rule)
    {
        self::$message = "Minimum " . $rule . " symbols�";
        return !(mb_strlen($value) < $rule);
    }

    protected static function max_length ($value, $rule)
    {
        self::$message = "Maximum" . $rule . " symbols";

        return !(mb_strlen($value) > $rule);
    }

    protected static function required ($value, $rule)
    {
        self::$message = "This field is required";
        
        return $value != '';
    }

    public static function addError ($field, $err_msg = null)
    {
        if ($err_msg == null) {
            self::$errors[] = $field;
        } else {
            self::$errors[$field] = $err_msg;
        }
    }

    public static function isMail ($string)
    {
        return !(bool) preg_match('/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $string);
    }

    public static function testMail ($string)
    {
        self::$message = "Invalid email address";
        return !self::isMail($string) ? $string : null;
    }

    // valid - a-z A-Z 0-9 _ - .
    public static function hasInvalidChars ($string, $chars = '')
    {
        self::$message = "Invalid characters";
        if (is_array($chars))
            $chars = join('', $chars);
        return !preg_match("/^([{$chars}a-zA-Z0-9_\-\.]+)$/", $string);
    }

    public static function testChars ($string, $chars = '')
    {
        self::$message = "Invalid characters";
        return !self::hasInvalidChars($string, $chars) ? $string : null;
    }

    public static function textChars ($string, $chars)
    {
        self::$message = "Invalid characters";
        if (is_array($chars))
            $chars = join('', $chars);
        return preg_match("/^[{$chars}a-zA-Z\p{Cyrillic}\s]+$/u", $string);
    }

    public static function range ($value, $min = null, $max = null, $int = true)
    {
        if (isset($min) && isset($max) && $min > $max) {
            list($min, $max) = array($max, $min);
        }

        $value = $int ? (int) $value : (float) $value;

        return $min !== null && $value <= $min ? $min : ($max !== null && $value > $max ? $max : $value);
    }

    public static function between ($val, $min, $max, $inc = true)
    {
        if ($min > $max) {
            list($min, $max) = array($max, $min);
        }

        return (bool) (($val > $min && $val < $max) || ($inc && ($val == $min || $val == $max)));
    }

    /**
     * 	echo Validator::price('$100.30'); 		// 100.30
     * 	echo Validator::price('100 000');		// 100000.00
     * 	echo Validator::price('100 000.341');	// 100000.34
     * 	echo Validator::price('100 000.345');	// 100000.35
     * 	echo Validator::price('100 000.348');	// 100000.35
     */
    public static function price ($string)
    {
        return round((float) preg_replace('/[a-zA-Z: ,à$]+/', '', trim($string)), 2);
    }

    public static function filterArray ($array, $keys, $filter = true)
    {
        if (!is_array($array) || count($array) == 0) {
            return array();
        }

        $filtered = array();
        foreach ($keys as $key) {
            if (isset($array[$key])) {
                $filtered[$key] = $array[$key];
            } else if (!$filter) {
                $filtered[$key] = null;
            }
        }

        return $filtered;
    }

}
