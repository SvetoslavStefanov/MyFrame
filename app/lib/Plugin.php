<?php
/**
 * Description of Plugin
 *
 * @author svetlio
 */
abstract class Plugin {
    public $loaded_plugins = array();

    public function loadPlugin($names){
        if(!is_array($names)){
            $this->$names = new $names();
            $this->loaded_plugins[] = $names;
        }else{
            foreach($names as $key => $value){
                if(is_array($value)){
                    $this->$key = new $key($value);
                    $this->loaded_plugins[] = $key;
                }else{
                    $this->$key = new $key($value);
                    $this->loaded_plugins[] = $value;
                }
            }
        }
    }
}

?>
