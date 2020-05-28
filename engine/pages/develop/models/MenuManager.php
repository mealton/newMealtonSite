<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 07.03.2020
 * Time: 18:05
 */

class MenuManager extends main_Model
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

    private function getMenuOption($id)
    {
        $sql = 'SELECT * FROM `new_project_menu`
                  WHERE `id` = ' . $id . ' LIMIT 1';
        return db::getInstance()->Select($sql);
    }

    private function addMenuOption()
    {
        $sql = 'INSERT INTO `new_project_menu` 
                  (`menu_option`, `menu_option_url`) 
                    VALUES ("' . addslashes($this->arguments['menu_option']) . '", "' . $this->arguments['menu_option_url'] . '")';

        $id = db::getInstance()->QueryInsert($sql);
        return $id ? $this->getMenuOption($id) : false;
    }

    private function updateMenuOption()
    {
        $sql = 'UPDATE `new_project_menu` ';
        $set = '';
        foreach ($this->arguments as $field => $value){
            if($field == "action")
                continue;
            $set .= '`' . $field . '` = "' . addslashes($value) . '",';
        }
        $set = trim($set, ',');
        $sql .= ' SET ' . $set . ' WHERE `id` = ' . $this->arguments['id'];
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getMenuOption($this->arguments['id']) : false;
    }

    private function deleteMenuOption()
    {
        $sql = 'UPDATE `new_project_menu` SET `isActive` = "' . $this->arguments['isActive'] . '" WHERE `id` = ' . $this->arguments['id'];
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getMenuOption($this->arguments['id']) : false;
    }
    
}
