<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 15:24
 */
class PublicUpdateManager extends main_Model
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
        $category = intval($this->arguments['category']);
        $long_title = $this->arguments['long_title'] ? addslashes($this->arguments['long_title']) : $short_title;
        $description = addslashes($this->arguments['description']);
        $user_id = intval($this->arguments['user_id']);

        $hashtags = addslashes($this->arguments['hashtags']);
        $imported = addslashes($this->arguments['imported']);
        $token = rand(0, 1000000);


        //Обновляем заголовки публикации

        $sql = 'UPDATE `new_project_publications` SET
                  `alias` = "' . $alias . '",
                  `category` = ' . $category . ',
                  `short_title` = "' . $short_title . '",
                  `long_title` = "' . $long_title . '",
                  `image_default` = "' . $image_default . '",
                  `description` = "' . $description . '",
                  `user_id` = ' . $user_id . ',
                  `hashtags` = "' . $hashtags . '",
                  `imported` = "' . $imported . '",
                  `token` = ' . $token . ' 
                  WHERE `id` = ' . $this->arguments['post_id'];

        db::getInstance()->Query($sql);


        //Добавляем новые гэги и удаляем старые


        if ($hashtags && is_array(explode(',', $hashtags))) {

            $sql = "INSERT INTO `new_project_hashtags` (`hashtag`,`public_id`,`token`) VALUES ";

            foreach (explode(',', $hashtags) as $hashtag) {
                if (trim($hashtag))
                    $sql .= '("' . addslashes(trim(mb_strtolower($hashtag))) . '",' . $this->arguments['post_id'] . ', ' . $token . '),';
            }

            $result = db::getInstance()->QueryInsert(trim($sql, ','));

            if ($result) {
                $sql = 'DELETE FROM `new_project_hashtags`
                      WHERE `public_id` = ' . $this->arguments['post_id'] . ' AND `token` != ' . $token;
                db::getInstance()->Query($sql);
            }
            
        }


        //Добавляем новый контент и удаляем старый

        $sql = 'INSERT INTO `new_project_publications_content`
                  (`publication_id`,`tag_category`,`content`,`style`,`token`,`isHidden`)
                    VALUES ';
        $values = '';
        foreach ($this->arguments['fields'] as $field) {
            $tag = $field['field'];
            $content = addslashes($field['value']);
            $style = $field['style'];
            $isHidden = $field['isHidden'];
            $values .= '(' . $this->arguments['post_id'] . ',"' . $tag . '","' . $content . '","' . $style . '",' . $token . ',' . $isHidden . '),';
        }
        $sql .= trim($values, ',');
        $result = db::getInstance()->QueryInsert($sql);

        if ($result) {
            $sql = 'DELETE FROM `new_project_publications_content`
                      WHERE `publication_id` = ' . $this->arguments['post_id'] . ' AND `token` != ' . $token;
            db::getInstance()->Query($sql);
        }

        $this->data = $result ? $this->getPublication($this->arguments['post_id']) : false;
    }

}