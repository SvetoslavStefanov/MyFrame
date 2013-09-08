<?
class Dispatcher{
    private $defaultController;
    private $defaultAction;
    private $defaultId;
    public static $in_admin = false;

   public function  __construct($defaultController, $defaultAction, $defaultId = null){
        $this->defaultController = $defaultController;
        $this->defaultAction = $defaultAction;
        $this->defaultId = $defaultId;
   }

   public function dispatch($url){
       list($controllerName, $actionName, $id) = $this->getParams($url);

       $class = $controllerName.'Controller';

       if (!class_exists($class)){
            throw new NotFoundException("class {$class} doesn't exists.");
        }

       $controller = new $class;

       if(!method_exists($controller, $actionName.'Action')){
           throw new NotFoundException("Method {$actionName} doesn't exists");
       }

       $controller->dispatch($controllerName, $actionName, $id);
   }

   private function cleanUrl($url){
       $url = trim(substr($url, strlen(PUBLIC_DIR) + 1),'/');
       $url = explode('/', $url);
       foreach($url as $key => $val)
           if($val = strstr($val, '?', true))
               $url[$key] = $val;

       $this->cleanForAdmin($url);

       return $url;
   }

   private function cleanForAdmin(&$url){
       foreach($url as $key => $value){
           if(ADMIN_DIR == $value){
               unset($url[$key]);
               self::$in_admin = true;

               if(!empty($url)){
                    $url[$key+1] = ADMIN_DIR.'_'.$url[$key+1];
               }else{
                   $url[] = 'admin_Sign';
                   $url[] = 'in';
               }
               break;
           }
       }

       return $url;
   }

   public function getParams($url){
       $url = $this->cleanUrl($url);

       if (!$controllerName = array_shift($url)){
            return array($this->defaultController, $this->defaultAction, $this->defaultId);
       }

       if(!$actionName = array_shift($url)){
            return array($this->defaultController, $this->defaultAction, $this->defaultId);
       }

       if(count($url) > 2){
           $controllerName = array_shift($url);
           $actionName = array_shift($url);
       }

       return array($controllerName, $actionName, array_shift($url));
   }

}