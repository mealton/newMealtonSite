<?php


class Likes extends main_Model
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
        switch($this->arguments['act']){
            case ('default_like'):
                $sql = 'UPDATE `new_project_publications`
                    SET `likes` = `likes` - 1 
                        WHERE `id` = ' . $this->arguments['id'];
                break;
            case ('set_like'):
                $sql = 'UPDATE `new_project_publications`
                    SET `likes` = `likes` + 1 
                        WHERE `id` = ' . $this->arguments['id'];
                break;
            case ('default_dislike'):
                $sql = 'UPDATE `new_project_publications`
                    SET `dislikes` = `dislikes` - 1 
                        WHERE `id` = ' . $this->arguments['id'];
                break;
            case ('set_dislike'):
                $sql = 'UPDATE `new_project_publications`
                    SET `dislikes` = `dislikes` + 1 
                        WHERE `id` = ' . $this->arguments['id'];
                break;
        }

        db::getInstance()->Query($sql);
        $sql = 'SELECT `likes`, `dislikes`, `id` as `publication_id`
                    FROM `new_project_publications`
                         WHERE `id` = ' . $this->arguments['id'] . '
                            LIMIT 1';
        $this->data = db::getInstance()->Select($sql);
        return true;
    }




}