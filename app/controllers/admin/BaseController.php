<?php
/**
 * Description of BaseController
 *
 * @author svetlio
 */
class admin_BaseController extends Controller{
    protected $adminUser;

    protected function before(){
        $this->controllerName = str_replace('_', '/', $this->controllerName);
        $this->levelAccess();
        $this->confimAdminLogged();
        Dispatcher::$in_admin = true;
    }

    protected function levelAccess(){
        if(isset ($_SESSION['isAdmin'])){
            $this->adminUser = Sign::get($_SESSION['isAdmin']);
            return true;
        }else{
            return false;
        }
    }

    protected function confimAdminLogged(){
        if(empty($this->adminUser) && $this->controllerName != 'admin/Sign' && $this->actionName != 'in'){
            $this->redirect('Sign/in');
        }
    }
}

?>
