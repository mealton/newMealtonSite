<?php


class get_Comments extends main_Model
{
    private $arguments;
    private $data;

    public function get()
    {
        return $this->data;
    }

    public function set($arguments = array())
    {
        $this->arguments = $arguments;
    }

    public function execute()
    {
        $sql = "SELECT * FROM `comments`
                    WHERE `category` = 'gallery'
                        AND `status` != 'deleted'
                            AND `comment` != ''";
        $this->data = db::getInstance()->Select($sql);
    }
}