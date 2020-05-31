<?php


class get_Prev_Next_Public extends main_Model
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
        $this->data = $this->prevNext();
    }

    private function prevNext()
    {
        session_start();
        $created_on = $this->arguments['created_on'];
        $condition = $_SESSION['condition'];
        $sql_prev = $sql_next = '';

       if ($condition['condition'] == 'category' && $condition['value']){
            $sql_prev = "SELECT `posts`.`id` as `public_id`, 
                            `posts`.`alias`, 
                            `posts`.`alias`, 
                            `posts`.`long_title`, 
                            `posts`.`image_default`, `posts`.`description`,
                          (SELECT `content` FROM `new_project_publications_content`
                                WHERE `publication_id` = `posts`.`id` AND `tag_category` = 'image'
                                  ORDER BY RAND() LIMIT 1) as `random_img`                  
                                    FROM `new_project_publications` as `posts`
                                      RIGHT JOIN `new_project_publications_rubrics` as `category` ON `posts`.`category` = `category`.`id`
                                        WHERE `posts`.`created_on` > '$created_on' AND `posts`.`status` != 'deleted'
                                          AND `category`.`rubric_url_name` = '$condition[value]'
                                          ORDER BY `posts`.`created_on` LIMIT 1";
            $sql_next = "SELECT `posts`.`id` as `public_id`, 
                            `posts`.`alias`, 
                            `posts`.`alias`, 
                            `posts`.`long_title`, 
                            `posts`.`image_default`,  `posts`.`description`,
                          (SELECT `content` FROM `new_project_publications_content`
                                WHERE `publication_id` = `posts`.`id` AND `tag_category` = 'image'
                                  ORDER BY RAND() LIMIT 1) as `random_img`                  
                                    FROM `new_project_publications` as `posts` 
                                       RIGHT JOIN `new_project_publications_rubrics` as `category` ON `posts`.`category` = `category`.`id`
                                          WHERE `posts`.`created_on` < '$created_on' AND `posts`.`status` != 'deleted'
                                            AND `category`.`rubric_url_name` = '$condition[value]'
                                              ORDER BY `posts`.`created_on` DESC LIMIT 1";
        }
        elseif ($condition['condition'] == 'hashtags' && $condition['value']){
            $sql_prev = "SELECT `posts`.`id` as `public_id`, 
                            `posts`.`alias`, 
                            `posts`.`alias`, 
                            `posts`.`long_title`, 
                            `posts`.`image_default`, `posts`.`description`,
                          (SELECT `content` FROM `new_project_publications_content`
                                WHERE `publication_id` = `posts`.`id` AND `tag_category` = 'image'
                                  ORDER BY RAND() LIMIT 1) as `random_img`                  
                                    FROM `new_project_publications` as `posts`
                                      LEFT JOIN `new_project_hashtags` as `tags` ON `tags`.`public_id` = `posts`.`id`
                                        WHERE `posts`.`created_on` > '$created_on' AND `posts`.`status` != 'deleted'
                                          AND `tags`.`hashtag` = \"" . addslashes($condition['value']) . "\"
                                          ORDER BY `posts`.`created_on` LIMIT 1";
            $sql_next = "SELECT `posts`.`id` as `public_id`, 
                            `posts`.`alias`, 
                            `posts`.`alias`, 
                            `posts`.`long_title`, 
                            `posts`.`image_default`,  `posts`.`description`,
                          (SELECT `content` FROM `new_project_publications_content`
                                WHERE `publication_id` = `posts`.`id` AND `tag_category` = 'image'
                                  ORDER BY RAND() LIMIT 1) as `random_img`                  
                                    FROM `new_project_publications` as `posts` 
                                       LEFT JOIN `new_project_hashtags` as `tags` ON `tags`.`public_id` = `posts`.`id`
                                          WHERE `posts`.`created_on` < '$created_on' AND `posts`.`status` != 'deleted'
                                            AND `tags`.`hashtag` = \"" . addslashes($condition['value']) . "\"
                                              ORDER BY `posts`.`created_on` DESC LIMIT 1";
        }
        elseif ($condition['condition'] == 'find' && $condition['value']){


            $search = "AND REPLACE(REPLACE(`posts`.`long_title`, ' ', ''), '&nbsp;', '') LIKE \"%" . str_replace(' ', '', addslashes($condition['value'])) . "%\"";

            $sql_prev = "SELECT `posts`.`id` as `public_id`, 
                            `posts`.`alias`, 
                            `posts`.`alias`, 
                            `posts`.`long_title`, 
                            `posts`.`image_default`, `posts`.`description`,
                          (SELECT `content` FROM `new_project_publications_content`
                                WHERE `publication_id` = `posts`.`id` AND `tag_category` = 'image'
                                  ORDER BY RAND() LIMIT 1) as `random_img`                  
                                    FROM `new_project_publications` as `posts`
                                        WHERE `posts`.`created_on` > '$created_on' AND `posts`.`status` != 'deleted'
                                          $search
                                          ORDER BY `posts`.`created_on` LIMIT 1";
            $sql_next = "SELECT `posts`.`id` as `public_id`, 
                            `posts`.`alias`, 
                            `posts`.`alias`, 
                            `posts`.`long_title`, 
                            `posts`.`image_default`,  `posts`.`description`,
                          (SELECT `content` FROM `new_project_publications_content`
                                WHERE `publication_id` = `posts`.`id` AND `tag_category` = 'image'
                                  ORDER BY RAND() LIMIT 1) as `random_img`                  
                                    FROM `new_project_publications` as `posts` 
                                          WHERE `posts`.`created_on` < '$created_on' AND `posts`.`status` != 'deleted' 
                                            $search
                                              ORDER BY `posts`.`created_on` DESC LIMIT 1";
        }else{
           $sql_prev = "SELECT `posts`.`id` as `public_id`, `posts`.`alias`, `posts`.`alias`, `posts`.`long_title`, `posts`.`image_default`, `posts`.`description`,
                          (SELECT `content` FROM `new_project_publications_content`
                                WHERE `publication_id` = `posts`.`id` AND `tag_category` = 'image'
                                  ORDER BY RAND() LIMIT 1) as `random_img`                  
                                    FROM `new_project_publications` as `posts`
                                     WHERE `posts`.`created_on` > '$created_on' AND `posts`.`status` != 'deleted' AND `posts`.`category` != 5 ORDER BY `posts`.`created_on` LIMIT 1";
           $sql_next = "SELECT `posts`.`id` as `public_id`, `posts`.`alias`, `posts`.`alias`, `posts`.`long_title`, `posts`.`image_default`,  `posts`.`description`,
                          (SELECT `content` FROM `new_project_publications_content`
                                WHERE `publication_id` = `posts`.`id` AND `tag_category` = 'image'
                                  ORDER BY RAND() LIMIT 1) as `random_img`                  
                                    FROM `new_project_publications` as `posts`                                  
                                              WHERE `posts`.`created_on` < '$created_on' AND `posts`.`status` != 'deleted' AND `posts`.`category` != 5 ORDER BY `posts`.`created_on` DESC LIMIT 1";
       }

        //main_Model::pre($sql_prev);

        return array(
            'prev' => db::getInstance()->Select($sql_prev),
            'next' => db::getInstance()->Select($sql_next)
        );
    }

}