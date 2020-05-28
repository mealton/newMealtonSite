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
        $this->data = array(
            'menu-options' => db::getInstance()->Select('SELECT * FROM `new_project_menu`'),
            'users' => db::getInstance()->Select('SELECT * FROM `new_project_users`'),
            'rubrics' => db::getInstance()->Select('SELECT * FROM `new_project_publications_rubrics`'),
            'publications' => db::getInstance()->Select('SELECT * FROM `new_project_publications`')
        );
    }

}