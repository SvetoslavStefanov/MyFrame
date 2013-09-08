<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BreadCrumbTrail
 *
 * @author svetlio
 */
class BreadCrumbTrail {
    protected static $trail;

    public static function add($label, $url){
        self::$trail[] = array('label' => $label, 'url' =>$url);
    }

    public static function get(){
        $all_trails = array();
        $all_trails[] = link_to("/", "Home");
        foreach(self::$trail as $trail){
            $all_trails[] = link_to($trail['url'], $trail['label']);
        }

        return join(" > ", $all_trails);
    }
}

?>
