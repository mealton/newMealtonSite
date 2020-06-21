<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 15:24
 */
class PublicManager extends main_Model
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

    private function getPublication($id)
    {
        $sql = "SELECT * FROM `new_project_publications` WHERE `id` = " . $id;
        return db::getInstance()->Select($sql);
    }

    public function execute()
    {
        $short_title = addslashes($this->arguments['short_title']);
        $alias = addslashes($this->arguments['alias']);
        $image_default = addslashes($this->arguments['image_default']);
        $category = intval($this->arguments['category']) ? intval($this->arguments['category']) : 1;
        $long_title = $this->arguments['long_title'] ? addslashes($this->arguments['long_title']) : $short_title;
        $description = addslashes($this->arguments['description']);
        $user_id = intval($this->arguments['user_id']);

        $hashtags =  addslashes($this->arguments['hashtags']);
        $imported =  addslashes($this->arguments['imported']);
        $token = rand(0, 10000);

        $long_title = $long_title ? $long_title : $short_title;

        $sql = 'INSERT INTO `new_project_publications` 
                  (`alias`,`category`,`short_title`,`long_title`,`image_default`,`description`,`user_id`,`hashtags`,`imported`,`token`)
                    VALUES ("' . $alias . '",' . $category . ',"' . $short_title . '","' . $long_title . '","' . $image_default . '","' . $description . '",' . $user_id . ',"' . $hashtags . '","' . $imported . '",' . $token .')';

        $id = db::getInstance()->QueryInsert($sql);

        $sql = 'INSERT INTO `new_project_publications_content`
                  (`publication_id`,`tag_category`,`content`,`style`,`token`)
                    VALUES ';
        $values = '';
        foreach ($this->arguments['fields'] as $field){
            $tag = $field['field'];
            $content = addslashes($field['value']);
            $style = $field['style'];
            $values .= '(' . $id . ',"' . $tag . '","' . $content . '","' . $style . '",' . $token . '),';
        }
        $sql .= trim($values, ',');
        db::getInstance()->QueryInsert($sql);

        $sql = 'INSERT INTO `new_project_hashtags` (`hashtag`,`public_id`) VALUES ';

        foreach (explode(',', $hashtags) as $hashtag){
            $sql .= '("' . addslashes(mb_strtolower(trim($hashtag))) . '",' . $id . '),';
        }

        $result = db::getInstance()->QueryInsert(trim($sql, ','));


        $this->data = $result ? $this->getPublication($id) : false;
    }

}