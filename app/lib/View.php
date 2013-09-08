<?php

class View
{

    private $variables = array();
    private $layout;
    public static $tpl = 'default';

    function __construct ($layout = null)
    {
        $this->layout = $layout;
    }

    function assign ($var, $value = null)
    {
        if (!is_array($var)) {
            $this->variables[$var] = $value;
        } else {
            foreach ($var as $key => $value) {
                $this->variables[$key] = $value;
            }
        }
    }

    function render ($template, $layout = true)
    {
        $this->template = self::$tpl . '/' . $template;

        if ($layout === true) {
            $layout = $this->layout;
        }

        extract($this->variables);

        $in_admin = Dispatcher::$in_admin === true ? ADMIN_DIR . '/' : '';

        include VIEWS_DIR . '/' . self::$tpl . '/' . $in_admin . ($layout ? $layout : $this->template) . '.php';
    }

}