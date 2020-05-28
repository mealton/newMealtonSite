<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 15:24
 */
class ProfileManager extends main_Model
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
        $action = $this->arguments['action'];
        $this->data = $this->$action();
    }

    private function getUser($id)
    {
        $sql = 'SELECT * FROM `new_project_users`
                  WHERE `id` = ' . $id . ' LIMIT 1';
        return db::getInstance()->Select($sql);
    }


    private function updateUser()
    {
        $sql = 'UPDATE `new_project_users` 
                  SET `' . $this->arguments['field'] . '` = "' . $this->arguments['value'] . '"
                        WHERE `id` = ' . $this->arguments['id'];
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getUser($this->arguments['id']) : false;
    }
}