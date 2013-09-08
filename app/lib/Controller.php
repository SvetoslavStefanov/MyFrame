<?php

class Controller extends Plugin
{

    public $controllerName;
    public $actionName;
    public $id;
    public $model;
    public $rendered = false;
    protected $layout = 'layout';
    protected $before;
    public static $default_pic;
    public $message = null;

    public function dispatch ($controllerName, $actionName, $id)
    {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->id = $id;

        $this->before();

        foreach ((array) $this->before as $method => $names) {
            if ((is_array($names) && in_array($actionName, $names)) || $actionName == $names) {
                $this->{$method}();
            }
        }

        //$this->components = new Components();

        $this->action($actionName, $id);
    }

    public function action ($actionName, $id = null)
    {
        $this->{$actionName . 'Action'}($id);

        if (!$this->rendered) {
            $this->render($actionName);
        }
    }

    /*
     * @Description
     * Set message into session variable, so to be readed in layout.php
     */

    protected function setMessage ()
    {
        if ($this->message != null) {
            $this->message = \addslashes($this->message);
            $_SESSION['message'] = $this->message;
        }
    }

    protected function render ($template, $layout = true)
    {
        $view = new View();
        $this->setMessage();
        $view->assign(get_object_vars($this));

        $view->render(
                $template = $this->controllerName . '/' . $template, $layout === true ? $this->layout : $layout
        );
        $this->rendered = true;
    }

    /* protected function get($key, $default = null){
      return isset($_GET[$key]) ? $_GET[$key] : $default;
      } */

    protected function get ($key, $default = null, $stripslashes = true)
    {
        if ($stripslashes && isset($_GET[$key]) && \is_array($_GET[$key])) {
            //array_map("stripslashes", $_POST[$key]);
            foreach ($_GET[$key] as $keyy => $value) {
                $_POST[$key][$keyy] = \stripslashes($value);
            }
        }

        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    protected function post ($key, $default = null, $stripslashes = true)
    {
        if ($stripslashes && isset($_POST[$key]) && \is_array($_POST[$key])) {
            //array_map("stripslashes", $_POST[$key]);
            foreach ($_POST[$key] as $keyy => $value) {
                $_POST[$key][$keyy] = \stripslashes($value);
            }
        }

        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    protected function redirect ($url)
    {
        $this->setMessage();
        header('Location: ' . url($url));
        exit;
    }

    protected function back ()
    {
        if (isset($_SERVER['HTTP_REFERER']))
            $this->redirect($_SERVER['HTTP_REFERER']);
        else
            $this->redirect('/');
    }

    protected function before ()
    {
        if (isset($_COOKIE['user_cookie']) && $_COOKIE['user_cookie'] != 'out') {
            $cookie = unserialize(stripslashes($_COOKIE['user_cookie']));
            $this->currentUser = Sign::find(array('where' => array('id' => $cookie['user_id']))); //Sign::get($cookie['userId']);
        }
    }

    public function confirmLogged ()
    {
        if (empty($this->currentUser)) {
            $_SESSION['reference'] = $_SERVER['REQUEST_URI'];
            $this->redirect('Sign/in');
        }
    }

    protected function privatePage ()
    {
        if ($this->currentUser->id != $this->id) {
            $this->back();
        }
    }

}