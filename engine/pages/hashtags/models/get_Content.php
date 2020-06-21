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
        session_start();
        $page = count($this->arguments) > 2 ? end($this->arguments) : 1;
        $limit =  $this->limit;
        $offset = $limit * ($page - 1);

        $sql = "SELECT `publics`.*,`publics`.`id` as `public_id`, `users`.`username`, `users`.`profile_image`, `cat`.*,
                  (SELECT COUNT(*) FROM `new_project_comments` WHERE `post_id` = `publics`.`id` AND `status` = 1) as `commentsCount`,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'image') as `count_img`,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'video') as `count_video`,
                  (SELECT `content` FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'image'
                      ORDER BY RAND() LIMIT 1) as `random_img`,
                      `tags`.`hashtag` as `hashtag`,
                          (SELECT CEILING(COUNT(`p`.`id`)/" . $limit . ") FROM `new_project_publications` as `p` 
                            LEFT JOIN `new_project_hashtags` as `tags` ON `tags`.`public_id` = `p`.`id`
                              WHERE `p`.`status` != 'deleted' 
                                    AND `tags`.`hashtag` = \"" . addslashes($this->arguments[1]) . "\") AS `pages`
                          FROM `new_project_publications` as `publics`
                            LEFT JOIN  `new_project_users` as `users` ON `publics`.`user_id` = `users`.`id`
                              LEFT JOIN  `new_project_publications_rubrics` as `cat` ON `publics`.`category` = `cat`.`id`
                                LEFT JOIN `new_project_hashtags` as `tags` ON `tags`.`public_id` = `publics`.`id`
                                  WHERE `publics`.`status` != 'deleted' 
                                    AND `tags`.`hashtag` = \"" . addslashes($this->arguments[1]) . "\"
                                        ORDER BY `publics`.`created_on` DESC LIMIT " . $limit . " OFFSET " . $offset;


        $this->data = db::getInstance()->Select($sql);
    }

    private function getPage($query)
    {
        foreach ($query as $item) {
            if (preg_match('/^page-/', $item)) {
                $page = intval(end(explode('-', $item)));
                return $page ? $page : 1;
            }
        }
        return 1;
    }

}