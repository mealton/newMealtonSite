<?php


class get_Tags extends main_Model
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
        $sql = "SELECT DISTINCT `hashtag` FROM `new_project_hashtags` WHERE `hashtag` != '' ORDER BY `hashtag`";
        $this->data = db::getInstance()->Select($sql);
    }


}