<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidationsController
 *
 * @author svetlio
 */
class admin_ValidationsController extends \admin_BaseController{
    var $before = array(
        'setValidation' => array('new', 'create', 'show'),
        'getValidation' => array('edit', 'update', 'destroy'),
        'getFormValidation' => array('new', 'edit', 'create', 'update')
    );

    protected function setValidation(){
        $this->validation = new admin_Validations();
    }

    protected function getValidation(){
        $this->validation = admin_Validations::get($this->id);
    }

    protected function getFormValidation(){
        $this->form_validations = admin_FormValidations::findAll(array('fields' => 'id, name'));
        $forms = array();
        $i=0;
        foreach($this->form_validations as $form){
            $forms[$i]['value'] = $form->id;
            $forms[$i]['content'] = $form->name;
            $i++;
        }

        $this->forms = $forms;
    }

    public function newAction(){
        $this->title = 'Create new validation';
        if(isset($_SESSION['selected_form'])){
            $this->validation->relation_id = $_SESSION['selected_form'];
        }
    }

    public function createAction(){
        if($this->validation->save($this->post('admin_Validations'))){
           $_SESSION['selected_form'] = $_POST['admin_Validations']['relation_id'];
           $this->redirect('Validations/show/' . $_POST['admin_Validations']['relation_id']);
        }else{
            $this->action('new');
        }
    }

    public function editAction(){
        $this->title = 'Edit';
    }

    public function updateAction(){
        if($this->validation->save($this->post('admin_Validations'))){
            $this->redirect("Validations/show/" . $_POST['admin_Validations']['relation_id']);
        }else{
            $this->action('edit');
        }
    }

    public function showAction(){
        $this->title = 'Validations';
        $this->validations = $this->validation->findAll(array('where' => array('relation_id' => $this->id), 'sort' => 'field DESC'));
    }

    public function destroyAction(){
        $relation_id = $this->validation->relation_id;
        $this->validation->destroy();
        $this->redirect('Validations/show/' . $relation_id);
    }
}

?>
