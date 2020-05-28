<?php


class Search extends main_Model
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
        $sql = "SELECT `long_title` 
                  FROM `new_project_publications` 
                    WHERE `status` != 'deleted'
                      AND `blocked` = 0
                        AND `long_title` LIKE \"%" . addslashes($this->arguments['value'])  . "%\"";

        $this->data = db::getInstance()->Select($sql);
    }


}