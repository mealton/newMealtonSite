<?php

class CommentLike extends main_Model
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
        if (!$this->arguments['id'])
            return false;

        db::getInstance()->Query($this->arguments['sql']);
        $sql = "SELECT `likes`, `dislikes`        
                    FROM `new_project_comments`
                         WHERE `id` = " . $this->arguments['id'] . "
                            LIMIT 1";
        $fetch = db::getInstance()->Select($sql);
        $this->data = $fetch[0];
        return true;
    }


}