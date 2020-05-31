<?php


class getNav extends main_Model
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
        $this->data = $this->getNavigation();
    }

    private function getNavigation()
    {
        $sql = "SELECT * 
                    FROM `new_project_publications_rubrics`
                        WHERE `isActive` != 'deleted' LIMIT 15";

        return array(
            'index' => array(
                'menu_option' => 'Главная',
                'menu_option_url' => 'index'
            ),
            'category' => array(
                'menu_option' => 'Категории',
                'menu_option_url' => 'category',
                'categories' => db::getInstance()->Select($sql)
            ),
            'options' => db::getInstance()->Select('SELECT * FROM `new_project_menu` WHERE `isActive` != "deleted"')
        );
    }


}