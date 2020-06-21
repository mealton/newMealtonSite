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

        //$likeDislike = $this->arguments['likeDislike'];

       /* $sql = "UPDATE `new_project_comments`
                    SET `$likeDislike` = `$likeDislike` + 1
                        WHERE `id` = " . $this->arguments['id'];*/
        $sql = '';

        switch($this->arguments['act']){
            case ('default_like'):
                $sql = 'UPDATE `new_project_comments`
                    SET `likes` = `likes` - 1 
                        WHERE `id` = ' . $this->arguments['id'];
                break;
            case ('set_like'):
                $sql = 'UPDATE `new_project_comments`
                    SET `likes` = `likes` + 1 
                        WHERE `id` = ' . $this->arguments['id'];
                break;
            case ('default_dislike'):
                $sql = 'UPDATE `new_project_comments`
                    SET `dislikes` = `dislikes` - 1 
                        WHERE `id` = ' . $this->arguments['id'];
                break;
            case ('set_dislike'):
                $sql = 'UPDATE `new_project_comments`
                    SET `dislikes` = `dislikes` + 1 
                        WHERE `id` = ' . $this->arguments['id'];
                break;
        }

        if($sql)        
            db::getInstance()->Query($sql);
        /*$sql = "SELECT `$likeDislike`
                    FROM `comments`
                         WHERE `id` = " . $this->arguments['id'] . "
                            LIMIT 1";
        $fetch = db::getInstance()->Select($sql);
        $this->data = $fetch[0];*/
        return true;
    }


}