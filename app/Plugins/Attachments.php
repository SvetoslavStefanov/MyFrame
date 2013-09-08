<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Attachments
 *
 * @author svetlio
 */
class Attachments extends \ActiveRecord{
    public $default_dir = 'user_files';
    public $dir;
    public $object;
    static $table;
    static $columns = array(
        'relation_id',
        'src',
        'type',
        'thumb'
    );

    public function __construct(){
        $this->generateDir();
        parent::__construct();
    }

    public function setTable (\ActiveRecord $object){
        $this->object = $object;
        $class = get_class($object);
        self::$table = $class::$table . "_attachments";
    }

    public function generateDir(){
        $time = getdate();
        $this->dir = $this->default_dir . '/' . $time['mon'].$time['year'];

        if(!is_dir($this->dir)){
            mkdir(PUBLIC_BASE_DIR . "/" . $this->dir, 0777, true);
        }
    }

    public function upload(\ActiveRecord $object, $file, $options = null,  $id = 0){
        $error = array_shift($file['error']);

        if(!is_array($file) || !empty($error)){
            return false;
        }

        //$this->setTable($object);
        $this->generateDir();

        $file = \array_map("array_shift", $file);

        if(!$src = Upload::file($file, $this->dir, $options)){
            \FormValidator::addError("Възникна грешка при качването на файла");
            return false;
        }

        if($id != 0){
            $this->delete($id);
        }

        return $this->saveFromForm($src, 1);
    }

    public function saveFromForm($src, $thumb = 0){
        if(!$this->save(array(
            'relation_id'   => $this->object->id,
            'src'           => $this->dir . "/" . $src,
            'type'          => \Upload::type($src),
            'thumb'         => $thumb
        ))){
            return false;
        }
        return $this->id;
    }

    public function delete($id){
        if(!$obj = $this->findById($id)){
            return false;
        }
        if(basename($obj->src) != basename(\Controller::$default_pic)){
            if($obj->thumb == 1){
                if(\file_exists($this->dir . "/thumb_".basename($obj->src))){
                    unlink($this->dir . "/thumb_".basename($obj->src));
                }
            }
            if(\file_exists($obj->src)){
                unlink($obj->src);
            }
        }

        return $obj->destroy();

    }

    public function getAttachment($id, $thumb = false){
        if(!$obj = $this->findById($id)){
            return false;
        }

        $src = explode("/", $obj->src);
        $file = array_pop($src);

        //$date_dir = array_pop($src);
        //$main_dir = "/" . $this->dir . "/";
        $main_dir = "/" . join("/", $src) . "/";

        return $thumb ? $main_dir . "thumb_" . $file : $main_dir . $file;
    }
}

?>
