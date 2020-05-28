<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 07.03.2020
 * Time: 14:38
 */
class Auth extends main_Model
{

    private $arguments;
    private $data;

    public function set($arguments = array())
    {
        $this->arguments = $arguments;
    }

    public function get()
    {
        return $this->data;
    }

    public function execute()
    {
        if(!$this->arguments['username'] || !$this->arguments['password'])
            return false;

        $username = addslashes($this->arguments['username']);
        $password = $this->arguments['cookie'] ? $this->arguments['password'] : md5($this->arguments['password']);
        $sql = "SELECT * FROM `new_project_users`
                    WHERE (`username` = \"" . $username . "\"  OR `email` = \"" . $username . "\")
                        AND `password` = '" . $password . "'";
        $this->data = db::getInstance()->Select($sql);
        return true;
    }

}