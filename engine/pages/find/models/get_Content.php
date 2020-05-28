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
        $page = count($this->arguments) > 2 ? end($this->arguments) : 1;
        $limit =  $this->limit;
        $offset = $limit * ($page - 1);

        $search = count($this->arguments) > 1 ? "AND REPLACE(REPLACE(`publics`.`long_title`, ' ', ''), '&nbsp;', '') LIKE \"%" . str_replace(' ', '', addslashes($this->arguments[1])) . "%\" 
                                                    OR REPLACE(REPLACE(`publics`.`short_title`, ' ', ''), '&nbsp;', '') LIKE \"%" . str_replace(' ', '', addslashes($this->arguments[1])) . "%\"" : "";


        $sql = "SELECT `publics`.*,`publics`.`id` as `public_id`, `users`.`username`, `users`.`profile_image`, `cat`.*,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'image') as `count_img`,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'video') as `count_video`,
                  (SELECT `content` FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'image'
                      ORDER BY RAND() LIMIT 1) as `random_img`,
                      (SELECT CEILING(COUNT(`id`)/$limit) FROM `new_project_publications` WHERE `status` != 'deleted' 
                          AND REPLACE(REPLACE(`long_title`, ' ', ''), '&nbsp;', '') LIKE \"%" . str_replace(' ', '', addslashes($this->arguments[1])) . "%\"
                            OR REPLACE(REPLACE(`short_title`, ' ', ''), '&nbsp;', '') LIKE \"%" . str_replace(' ', '', addslashes($this->arguments[1])) . "%\") as `pages`
                  FROM `new_project_publications` as `publics`
                    LEFT JOIN  `new_project_users` as `users` ON `publics`.`user_id` = `users`.`id`
                      LEFT JOIN  `new_project_publications_rubrics` as `cat` ON `publics`.`category` = `cat`.`id`
                        WHERE `publics`.`status` != 'deleted' 
                              $search
                            ORDER BY `publics`.`created_on` DESC LIMIT " . $limit . " OFFSET " . $offset;

        //main_Model::pre($sql);

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