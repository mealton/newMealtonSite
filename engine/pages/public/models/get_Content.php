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


    private function getPublic()
    {
        $id = $this->arguments['id'];
        $sql = 'UPDATE `new_project_publications`
                    SET `views` = `views` + 1 
                      WHERE `id` = ' . $id;
        db::getInstance()->Query($sql);

        $sql = 'SELECT `posts`.*, `content`.*, `users`.`username`, `users`.`profile_image`, `category`.`rubric_name`,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = ' . $id . ' AND `tag_category` = "image" AND `isHidden` != 1) as `count_img`,
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


        $data = db::getInstance()->Select($sql);

        $sql = 'SELECT `hashtag`, 
                  (SELECT COUNT(`id`) FROM `new_project_hashtags` WHERE `hashtag` = `h`.`hashtag`) as `count` 
                    FROM `new_project_hashtags` as `h`
                      WHERE `public_id` = ' . $id;

        $data[0]['hashtags-counter'] = db::getInstance()->Select($sql);

        return $data;
    }




}