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
class admin_Validations extends \ActiveRecord{
    public static $table = 'validations';
    static $columns = array(
        'relation_id',
        'field',
        'rule',
        'value'
    );

    protected function validate(){

        FormValidator::validate($this->relation_id, "relation_id", array('required' => true));
        FormValidator::validate($this->field, "field", array('required' => true));
        FormValidator::validate($this->rule, "rule", array('required' => true));
        FormValidator::validate($this->value, "value", array('required' => true));

        $this->value = htmlentities($this->value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
    }
}

?>
