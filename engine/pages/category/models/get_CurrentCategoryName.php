<?php


class get_CurrentCategoryName extends main_Model
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
        $sql = "SELECT `c`.`rubric_name` as `category`
                  FROM `new_project_publications_rubrics` as `c`
                    INNER JOIN `new_project_publications` as `p` ON `p`.`category` = `c`.`id`
                      WHERE `c`.`isActive` != 'deleted' AND `p`.`id` = " . $this->arguments;

        $this->data = db::getInstance()->Select($sql)[0]['category'];
    }




}