<?php

function array_cut (array &$array, $key, $default = null)
{
    if (!isset($array[$key])) {
        return $default;
    }

    $value = $array[$key];
    unset($array[$key]);

    return $value;
}

function getMessage ()
{
    if (isset($_SESSION['message'])) {
        echo "<div id=\"message\" class=\"notice success\"><div class=\"text\">" . $_SESSION['message'] . "</div></div>";
        unset($_SESSION['message']);
    }
}

function handle_exception ($e)
{
    if (ENVIROMENT == 'development') {
        echo '<div style="border: 2px solid red; padding: 10px;">';
        echo '<h2>', '<em>', get_class($e), '</em>: ', $e->getMessage(), '</h2>';
        echo '<pre>', $e->getTraceAsString(), '</pre>';
        echo '</div>';
    } else {
        $redirect = $e instanceof NotFoundException ? '404.html' : '500.html';

//                preg_replace("/#{1}[0-9]/s", "\n", $e->getTraceAsString());
//                echo $e ->getMessage();


        if (!headers_sent()) {
            header('Location: ' . SITE_URL . $redirect);
        } else {
            echo '<meta http-equiv="refresh" content="0;url=' . SITE_URL . $redirect . '" />';
        }
        exit;
    }
}

function d ($variable)
{
    echo '<div style="border: 1px dotted black; border-left: 2px solid; padding: 5px; margin: 10px; background: white;">';
    echo '<pre>';
    if (is_array($variable) || is_object($variable) || is_resource($variable)) {
        print_r($variable);
    } else if (is_bool($variable)) {
        echo '<strong style="color: green">', ($variable ? 'true' : 'false'), '</strong>';
    } else if ($variable === null) {
        echo '<strong style="color: red">null</strong>';
    } else {
        echo htmlspecialchars($variable);
    }
    echo '</pre>';
    echo '</div>';

    return $variable;
}

function url ($url)
{
    if ($url[0] == '/' || strpos($url, '://') !== false) {
        return $url;
    }

    $in_admin = Dispatcher::$in_admin === true ? ADMIN_DIR . '/' : '';

    return SITE_URL . '/' . $in_admin . join('/', array_filter(func_get_args()));
}

function tag ($tag, $options, $content = null)
{
    $test = array_cut($options, "error", "");

    foreach ($options as $key => $value) {
        $attributes[] = $key . ' = "' . $value . '"';
    }

    $attributes = join(' ', $attributes);

    return "<{$tag} {$attributes}" . ($content == null ? '/>' : ">" . trim($content) . "</{$tag}>");
}

function link_to ($url, $text, array $attributes = array())
{
    return tag('a', array_merge($attributes, array('href' => url($url), 'title' => $text)), $text);
}

function cleanUrl ()
{
    $parse_url = parse_url($_SERVER['REQUEST_URI']);
    $parsed_url = array_shift($parse_url);
    $url = explode('/', $parsed_url);
    array_shift($url);
    $admin = '';
    if ($url[0] == ADMIN_DIR)
        $admin = array_shift($url) . "/";

    $controller = array_shift($url);
    $action = array_shift($url);
    $id = count($url) > 0 ? "/" . array_shift($url) : '';
    $path = $admin . $controller . "/" . $action . $id;

    return $path;
}

function getContrAndActFromUrl ()
{
    $parse_url = parse_url($_SERVER['REQUEST_URI']);
    $parsed_url = array_shift($parse_url);
    $url = explode('/', $parsed_url);
    $url = array_reverse($url);
    array_pop($url);
    $admin = '';
    if (in_array('admin', $url)){
        $admin = array_pop($url);
    }

    if (in_array(PUBLIC_DIR, $url)){
        array_pop($url);
    }

    if (count($url) <= 1) {
        //return "/";
    }
    //unset last 3 parameters to clean the url
    foreach ($url as $key => $value) {
        if ($key > 2) {
            unset($url[$key]);
        }
    }
    $admin = strlen($admin) > 0 ? 'admin/' : '';
    $controller = array_pop($url);
    $action = count($url) > 0 ? "/" . array_pop($url) : "";
    //$id = count($url) > 0 ? "/".array_pop($url) : "";
    return $admin . $controller . $action;
}

/*
 * chose which tab to be active
 * param1: tab              array      on which case to be active the tab
 * param2: controllerName   string     the active controller
 * usage:
 * choseTab(array('InfoPages', 'show', 7), $controllerName);
 */

function choseTab (array $tab = array(), $data)
{
    /* d($_SERVER['REQUEST_URI']);
      d(join('/', $tab));
      d(count($tab));
      d($_SERVER['REQUEST_URI'] == "/" . join('/', $tab)); */

    for ($i = 0; $i < count($tab); $i++) {
        //d($tab[$i]);
        //d($data[$i]);
        if ($tab[$i] != $data[$i])
            break;
    }

    if ($i == count($tab)) {
        return 'active';
    }

    /* if(\cleanUrl($_SERVER['REQUEST_URI'], $action) == $tab){
      return 'active';
      }

      return ''; */
}

function admin_choseTab ($tab, $controllerName)
{
    $tab = explode(',', $tab);
    for ($i = 0; $i < count($tab); $i++) {
        $tab[$i] = "admin/" . $tab[$i];
    }

    if (in_array($controllerName, $tab))
        return 'current';
}

function genereteCode ()
{
    $random_string = 'qwertyuiopasdfghjklzxcvbnm1234567890' . time();
    $random_string = str_shuffle($random_string);
    $random_string = substr($random_string, '0', '31');

    return $random_string;
}

function removeFromUrl ($url, $to_remove)
{
    $custom_url = parse_url($url);
    if (!isset($custom_url['query']))
        return $url;
    $parts = explode($to_remove, $custom_url['query']);
    if (!isset($parts[1]))
        return $url;
    unset($custom_url['query']);
    $parts2 = explode("&", $parts[1]);

    $query = isset($parts2[1]) ? "?" . $parts[0] . $parts2[1] : '';


    return $custom_url['path'] . $query;
    /*
      $parts = explode($to_remove, $url);
      if(!isset($parts[1])) return $url;

      $parts['0'] = rtrim($parts['0'], '&');

      $parts2 = explode("&", $parts['1']);
      array_shift($parts2);

      $parts[1] = join('&', $parts2);
      $parts = join('', $parts);
      $parts = explode('?', $parts);

      if(empty($parts[1]))
      return $parts[0];

      return join('?', $parts);

     */
}

function addToUrl ($url, $to_add, $value)
{
    $parts = explode('?', $url);
    if (!isset($parts[1]))
        $parts[1] = $to_add . '=' . $value;
    else
        $parts[1] .= '&' . $to_add . '=' . $value;

    return join('?', $parts);
}