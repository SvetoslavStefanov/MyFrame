<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sign
 *
 * @author svetlio
 */
class Sign extends ActiveRecord
{

    static $table = 'user';
    static $columns = array(
        'username',
        'password',
        'email'
    );

    protected function registerValidate ()
    {
        FormValidator::validate($this->username, 'username', array("required" => true, "textChars" => '\,\.()0-9-\_\:\'\"'));
        FormValidator::validate($this->email, 'email', array("required" => true, "testMail" => ''));
        FormValidator::validate($this->password, 'password', array("required" => true));

        $user = $this->find(array('where' => array('username' => $this->username)));
        if ($user) {
            FormValidator::addError("username", "This username is already taken");
        }

        $user = $this->find(array('where' => array('email' => $this->email)));
        if ($user) {
            FormValidator::addError("email", "This email is already taken");
        }

        if (empty(FormValidator::$errors)) {
            $this->password = self::crypty($this->username, $this->password);
        }

    }

    public function loginValidate (array $array = array())
    {
        $username = array_cut($array, "username", "");
        $password = array_cut($array, "password", "");

        FormValidator::validate($username, "username", array("required" => 1));
        FormValidator::validate($password, "password", array("required" => 1));

        if (empty(\FormValidator::$errors)){
            $password = Sign::crypty($username, $password);
        }

        $obj = $this->find(array('where' => array(
                'username' => $username,
                'password' => $password
        )));

        if (!$obj && empty(\FormValidator::$errors)) {
            FormValidator::addError("Грешни потребителско име и/или парола");
        } else {
            return $obj;
        }

        return false;
    }

    public static function crypty ($username, $password)
    {
        return md5($username . '-myOwnsecret\/\/0r|)-' . $password);
    }

}

?>
