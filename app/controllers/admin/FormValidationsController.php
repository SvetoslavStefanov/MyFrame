<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validations
 *
 * @author svetlio
 */
class admin_FormValidationsController extends \admin_BaseController{
    var $before = array(
        'setValidation' => array('index', 'new', 'create'),
        'getValidation' => array('edit', 'update', 'destroy')
    );

    public function setValidation(){
        $this->formValidation = new admin_FormValidations();
    }

    public function getValidation(){
        $this->formValidation = admin_FormValidations::get($this->id);
    }

    public function newAction(){
        $this->title = 'Create new form';
    }

    public function createAction(){
        $accessible = array(
            'name',
            'address1',
            'address2'
        );
        if($this->formValidation->save($this->post('admin_FormValidations'))){
            $this->redirect("FormValidations/index");
        }else{
            $this->action('new');
        }
    }

    public function editAction(){
        $this->title = 'Edit';
    }

    public function updateAction(){
        if($this->formValidation->save($this->post('admin_FormValidations'))){
            $this->redirect("FormValidations/index");
        }else{
            $this->action('edit');
        }
    }

    public function indexAction(){
        $this->title = 'List Forms';
        $this->formValidations = $this->formValidation->findAll();
    }

    public function destroyAction(){
        $this->formValidation->destroy();
        $this->redirect("FormValidations/index");
    }
}

?>
