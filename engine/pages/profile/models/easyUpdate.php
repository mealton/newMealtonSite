<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 21.03.2020
 * Time: 12:34
 */
class easyUpdate extends main_Model
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
        switch($this->arguments['to_do']){
            case('unpublished'):
                $this->data = $this->unpublished($this->arguments['id']);
                break;
            case('published'):
                $this->data = $this->published($this->arguments['id']);
                break;
            case('delete'):
                $this->data = $this->delete($this->arguments['id']);
                break;
        }
    }

    private function getPublication($id)
    {
        $sql = "SELECT *, 
                  (SELECT `content` FROM `new_project_publications_content`
                    WHERE `publication_id` = `new_project_publications`.`id` AND `tag_category` = 'image'
                      ORDER BY RAND() LIMIT 1) as `random_img` 
                          FROM `new_project_publications` WHERE `id` = " . $id;
        return db::getInstance()->Select($sql);
    }

    private function unpublished($id){
        $sql = 'UPDATE `new_project_publications` SET
                    `status` = "deleted",
                    `deleted_on` = "' . date('Y-m-d H:i:s') . '"  
                  WHERE `id` = ' . $id;
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getPublication($id) : false;
    }

    private function published($id){
        $sql = 'UPDATE `new_project_publications` SET
                    `status` = "",
                    `deleted_on` = "0000-00-00 00:00:00",  
                    `created_on` = "' . date('Y-m-d H:i:s') . '"  
                  WHERE `id` = ' . $id;
        $result = db::getInstance()->Query($sql);
        return $result ? $this->getPublication($id) : false;
    }

    private function delete($id){
        $sql = 'SELECT `content` 
                      FROM `new_project_publications_content`
                        WHERE `tag_category` = "image" AND `publication_id` = ' . $id;
        $images = db::getInstance()->Select($sql);
        foreach ($images as $image){
            $preview = $image['content'];
            $fullsize = str_replace('preview', 'fullsize', $preview);
            if(file_exists($_SERVER['DOCUMENT_ROOT'] . $preview)){
                unlink($_SERVER['DOCUMENT_ROOT'] .$preview);
            }
            if(file_exists($_SERVER['DOCUMENT_ROOT'] . $fullsize)){
                unlink($_SERVER['DOCUMENT_ROOT'] . $fullsize);
            }
        }

        $sql = 'DELETE FROM `new_project_publications` WHERE `id` = ' . $id;
        $result = db::getInstance()->Query($sql);
        if($result){
            $sql = 'DELETE FROM `new_project_publications_content` WHERE `publication_id` = ' . $id;
            db::getInstance()->Query($sql);
        }

        return true;
    }

}