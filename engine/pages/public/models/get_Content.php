<?php


class get_Content extends main_Model
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
        $this->data = $this->getPublic();
    }

    /*private function getAll()
    {
        $filter = $this->arguments['filter'] == 'vse_publikacii' ? '' : " AND `posts`.`cat` = '" . $this->arguments['filter'] . "'";
        $sql = "SELECT `posts`.* FROM `posts`
                    WHERE `posts`.`status` != 'deleted'" . $filter . " 
                       ORDER BY `posts`.`id` DESC";
        return db::getInstance()->Select($sql);
    }*/

    /*private function getOne($url_name)
    {
        //Обновляем просмотры
        $sql = 'UPDATE `posts` 
                    SET `views` = `views` + 1 
                        WHERE `url_name` = "' . $url_name . '"';
        db::getInstance()->Query($sql);
        //Получаем данные по публикации
        $sql = 'SELECT `posts`.`title` as `title`,
                        `posts`.`post_id` as `post_id`, 
                        `posts`.`id` as `id`, 
                        `posts`.`author` as `author`, 
                        `posts`.`username` as `username`, 
                        `posts`.`import` as `import`, 
                        `posts`.`views` as `views`, 
                        `posts`.`likes` as `likes`, 
                            `documents`.`subtitle` as `subtitle`, 
                            `documents`.`image` as `image`, 
                            `documents`.`image_description` as `image_description`, 
                            `documents`.`video` as `video`, 
                            `documents`.`video_description` as `video_description`, 
                            `documents`.`text` as `text`
                                FROM `posts` RIGHT JOIN `documents`
                                    ON `posts`.`post_id` = `documents`.`post_id` 
                                        AND `posts`.`cat` = `documents`.`cat`
                                            WHERE `posts`.`url_name` = "' . $url_name . '"
                                                AND `posts`.`status` != "deleted"';
        return db::getInstance()->Select($sql);
    }*/


    private function getPublic()
    {
        $id = $this->arguments['id'];
        $sql = 'UPDATE `new_project_publications`
                    SET `views` = `views` + 1 
                      WHERE `id` = ' . $id;
        db::getInstance()->Query($sql);
        $sql = 'SELECT `posts`.*, `content`.*, `users`.`username`, `category`.`rubric_name`,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = ' . $id . ' AND `tag_category` = "image") as `count_img`,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = ' . $id . ' AND `tag_category` = "video") as `count_video`,
                  (SELECT CEILING((COUNT(`id`)+1)/' . $this->limit . ') 
                        FROM `new_project_publications` WHERE `created_on` > (SELECT `created_on` FROM `new_project_publications` WHERE `id` = ' . $id . ') 
                            AND `status` != "deleted" AND `category` != 5 AND (`short_title` != "" OR `long_title` != "") ORDER BY `created_on` DESC) as `page`
                    FROM `new_project_publications` as `posts`
                      RIGHT JOIN `new_project_publications_content` as `content` ON `posts`.`id` = `content`.`publication_id`
                        RIGHT JOIN `new_project_users` as `users` ON `posts`.`user_id` = `users`.`id`
                            RIGHT JOIN `new_project_publications_rubrics` as `category` ON `posts`.`category` = `category`.`id`
                    WHERE `posts`.`id` = ' . $id;
        return db::getInstance()->Select($sql);
    }

}