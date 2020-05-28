<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 07.03.2020
 * Time: 18:05
 */

class CategoryManager extends main_Model
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

    private function getCategory($id)
    {
        $sql = 'SELECT * FROM `new_project_publications_rubrics`
                  WHERE `id` = ' . $id . ' LIMIT 1';
        return db::getInstance()->Select($sql);
    }

    private function addCategory()
    {
        $sql = 'INSERT INTO `new_project_publications_rubrics` 
                  (`rubric_name`, `rubric_url_name`) 
                    VALUES ("' . addslashes($this->arguments['rubric_name']) . '", "' . $this->arguments['rubric_url_name'] . '")';

        $id = db::getInstance()->QueryInsert($sql);
        return $id ? $this->getCategory($id) : false;
    }

    private function updateCategory()
    {
        $sql = 'UPDATE `new_project_publications_rubrics` ';
        $set = '';
        foreach ($this->arguments as $field => $value){
            if($field == "action")
                continue;
            $set .= '`' . $field . '` = "' . addslashes($value) . '",';
        }
        $set = trim($set, ',');
        $sql .= ' SET ' . $set . ' WHERE `id` = ' . $this->arguments['id'];
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getCategory($this->arguments['id']) : false;
    }

    private function deleteCategory()
    {
        $sql = 'UPDATE `new_project_publications_rubrics` SET `isActive` = "' . $this->arguments['isActive'] . '" WHERE `id` = ' . $this->arguments['id'];
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getCategory($this->arguments['id']) : false;
    }
    
}
