<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagination
 *
 * @author svetlio
 */
class Pagination {
    public $per_page = 10;//items per page, by default are 10
    public $cur_page = 1;//current page, by default is first
    public $count_items;
    private $url;
    private $object;
    
    /**
     * Return the limit in sql format
     * @param ActiveRecord $object - not used
     * @param type $options 
     *  Cur page - the current page, 1 by default
     *  per_page - results per page ( 10 by default)
     *  count_items - counted items to make limit 
     * @return type
     */
    public function getLimit(ActiveRecord $object = null, $options = array()){
        $this->object = $object;
        $this->cur_page = array_cut($options, 'cur_page', 1);
        $this->per_page = array_cut($options, 'per_page', $this->per_page);
        
        if($this->cur_page == 1 && isset($_GET['page']) && $_GET['page'] != 1)
            $this->cur_page = (int)$_GET['page'];
        
        if($this->object == null)
            $this->count_items = array_cut($options, 'count_items', 10);
        else
            $this->count_items = $this->object->count(array());
        
        
        $limit_begin = ($this->cur_page-1)*$this->per_page;
        $limit_end = $this->cur_page*$this->per_page;

        if($limit_begin > $this->count_items)    $limit_begin = $this->count_items-$this->per_page;
        if($limit_begin < 0) $limit_begin = 0;
        if($limit_end > $this->count_items) $limit_end = $this->count_items;
        //'sort' => 'id DESC' for documents ->showallAction TODO: fixed
        //return $this->object->findAll(array('sort' => 'id DESC', 'limit' => $limit_begin . "," .  $limit_end));
        return array($limit_begin . "," . $this->per_page);
    }
    
    public function getPages($class = null, $max_pages = null){
        $html = array();
        $url = cleanUrl();
        $url = $_SERVER["REQUEST_URI"];
        
        $symbol = strstr($_SERVER["REQUEST_URI"], "=") ? "&" : "?";
        $url = str_replace("{$symbol}page=" . $this->cur_page, '', $url);
        
        $page = strstr($_SERVER["REQUEST_URI"], "page=" . $this->cur_page);
        if($page == "page=" . $this->cur_page){
            $symbol = '?';
            $url = str_replace("{$symbol}page=" . $this->cur_page, '', $url);
            if(strstr($url, "=")) $symbol = '&';
        }
        
        
        $total_pages = (ceil($this->count_items/$this->per_page));
        
        $next = ($this->cur_page+1) > $total_pages ? $total_pages : $this->cur_page + 1;
        $prev = ($this->cur_page-1) <= 0 ? 1: $this->cur_page-1;
        if($class)
            $class = 'class = "' . $class . '"';
        $html = "<ul id=pagination {$class}>";
        $html .= "<li>" . link_to("{$url}{$symbol}page={$prev}", "Назад") . "</li>";
        for($i=1;$i < $total_pages+1;$i++){
             $option = array();
             
             if($this->cur_page == $i) $option = array('class' => 'active');
             $page = "<li>" . link_to("{$url}{$symbol}page={$i}", $i, $option) . "</li>";
             
             if(($this->cur_page <= 5 || $this->cur_page >  ($total_pages - 2))){
                 if($i == 5)
                         $page = $page . "<li><span>...</span></li>";
                 if($i > 5 && $i < ($total_pages - 1))
                     continue;
                 
                 $html .= $page ;
             }else{
                 if($i == 2)
                     $page = $page . "<li><span>...</span></li>";
                 elseif($i == ($total_pages - 1))
                     $page = "<li><span>...</span></li>" . $page;
                 
                 if($i < 3 || $i > ($total_pages - 2)){
                    $html .=  $page . "\n\r";
                 }else{
                     if($this->cur_page - 1 == $i)
                         $html .=  $page . "\n\r";
                     elseif($this->cur_page + 1 == $i)
                         $html .=  $page . "\n\r";
                     elseif($this->cur_page == $i)
                         $html .=  $page . "\n\r";
                 }      
             }
        }
        $html .= "<li>" . link_to("{$url}{$symbol}page={$next}", "Напред") . "</li>";
        $html .= "</ul>";
        if($total_pages < 2) return '';
        
        return $html;
    }
}

?>
