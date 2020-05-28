<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 07.03.2020
 * Time: 18:05
 */
class UserManager extends main_Model
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

    private function addUser()
    {
        $sql = 'INSERT INTO `new_project_users` ';
        $fields = $values = '';
        foreach ($this->arguments as $field => $value){
            if($field == "action")
                continue;
            $fields .= '`' . $field . '`,';
            $values .= '"' . ($field == 'password' ? md5($value) : addslashes($value)) . '",';
        }
        $fields = trim($fields, ',');
        $values = trim($values, ',');
        $sql .= '(' . $fields . ') VALUES (' . $values . ')';
        $id = db::getInstance()->QueryInsert($sql);
        return $id ? $this->getUser($id) : false;
    }

    private function updateUser()
    {
        $sql = 'UPDATE `new_project_users` ';
        $set = '';
        foreach ($this->arguments as $field => $value){
            if($field == "action")
                continue;
            $set .= '`' . $field . '` = "' . addslashes($value) . '",';
        }
        $set = trim($set, ',');
        $sql .= ' SET ' . $set . ' WHERE `id` = ' . $this->arguments['id'];
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getUser($this->arguments['id']) : false;
    }

    private function deleteUser()
    {
        $sql = 'UPDATE `new_project_users` 
                  SET `isActive` = "' . $this->arguments['isActive'] . '", 
                    `date_deleted` = ' . ($this->arguments['isActive'] == 'deleted' ? 'CURRENT_TIME' : '""') . '
                        WHERE `id` = ' . $this->arguments['id'];
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getUser($this->arguments['id']) : false;
    }

    private function resetPassword()
    {
        $query = $this->getUser($this->arguments['id']);
        $sql = 'UPDATE `new_project_users` SET `password` = MD5("' . $query[0]['username'] . '") WHERE `id` = ' . $this->arguments['id'];
        $result = db::getInstance()->Query($sql);
        return $result;
    }

}