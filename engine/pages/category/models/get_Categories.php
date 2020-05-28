<?php


class get_Categories extends main_Model
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
        $sql = "SELECT `cat`.`rubric_name` as `category`, `cat`.`rubric_url_name` as `category_alias`, 
                  `pub`.`id` AS `id`, `pub`.`alias` AS `alias`, `pub`.`short_title` as `title`, `pub`.`description` as `description`
                      FROM `new_project_publications_rubrics` AS `cat`
                        RIGHT JOIN `new_project_publications` AS `pub` 
                          ON `cat`.`id` = `pub`.`category`
                            WHERE `cat`.`isActive` != 'deleted' 
                              AND `pub`.`status` != 'deleted' 
                                AND `pub`.`blocked` = 0 
                                  AND `pub`.`short_title` != ''
                                     ORDER BY `pub`.`created_on` DESC";

        $this->data = $this->get_categories_array(db::getInstance()->Select($sql));
    }

    private function get_categories_array($data)
    {
        $result = array();
        $i = 1;
        foreach ($data as $row) {
            if (array_key_exists($row['category'], $result)) {
                $result[$row['category']]['publics'][] = array(
                    'title' => $row['title'],
                    'alias' => $row['alias'],
                    'id' => $row['id'],
                    'description' => $row['description']
                );
            } else {
                $result[$row['category']] = array(
                    'category' => $row['category'],
                    'category_alias' => $row['category_alias'],
                    'publics' => array(
                        array(
                            'title' => $row['title'],
                            'alias' => $row['alias'],
                            'id' => $row['id'],
                            'description' => $row['description']
                        )
                    )
                );
            }
        }
        ksort($result);
        return $result;
    }


}