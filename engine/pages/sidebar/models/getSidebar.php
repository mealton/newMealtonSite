<?php


class getSidebar extends main_Model
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
        $limit = array_key_exists('add', $this->arguments) ? "LIMIT 1 OFFSET " . $this->arguments['offset'] : "LIMIT 6";

        $sql = "SELECT `publics`.*,`publics`.`id` as `public_id`,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'image' AND `isHidden` != 1) as `count_img`,
                  (SELECT COUNT(*) FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'video') as `count_video`,
                  (SELECT `content` FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'image'
                      ORDER BY RAND() LIMIT 1) as `random_img`,
                           (SELECT COUNT(*) FROM `new_project_publications` 
                              WHERE `status` != 'deleted' AND `category` != 5) as `counter`
                          FROM `new_project_publications` as `publics`                            
                                WHERE `publics`.`status` != 'deleted' 
                                      AND `publics`.`category` != '5'
                                    ORDER BY `publics`.`created_on` DESC $limit";

        $this->data = db::getInstance()->Select($sql);
    }

}