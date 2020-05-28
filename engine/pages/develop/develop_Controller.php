<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 07.03.2020
 * Time: 16:00
 */
class develop_Controller extends main_Controller
{

    public function __construct($config, $page)
    {
        session_start();

        if(!$_SESSION['admin']){
            header('Location:/');
        }

        parent::__construct($config, $page);
    }

    public function get_page($config, $query = array())
    {
        $data = $this->executeModel($config, 'develop', 'get_Content', $query);
        $users = $data['users'];
        $menu_options = $data['menu-options'];
        $rubrics = $data['rubrics'];
        $publications = $data['publications'];
        $this->executeView('develop', array(
            array('view' => 'get_title', 'data' => array('title' => 'Зона администратора'), 'container' => 'title'),
            array('view' => 'menu_option', 'data' => $menu_options, 'container' => 'menu'),
            array('view' => 'user', 'data' => $users, 'container' => 'users'),
            array('view' => 'rubric', 'data' => $rubrics, 'container' => 'rubrics'),
            array('view' => 'get_footer')
        ));
    }
    
    
    
    

    protected function addUser($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'UserManager', $data);
        $new_user = $this->render('develop', array(
            'view' => 'user',
            'data' => $data
        ));
        print_r(json_encode(array('result' => !empty($data), 'data' => $new_user)));
    }

    protected function updateUser($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'UserManager', $data);
        print_r(json_encode(array('result' => !empty($data), 'data' => $data)));
    }

    protected function deleteUser($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'UserManager', $data);
        print_r(json_encode(array('result' => !empty($data), 'data' => $data)));
    }

    protected function resetPassword($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'UserManager', $data);
        print_r(json_encode(array('result' => !empty($data), 'data' => $data)));
    }

    protected function translit($config, $data)
    {
        print_r(json_encode(array('translit' => main_Model::translit($data['text']))));
    }

    protected function addMenuOption($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'MenuManager', $data);
        $new_option = $this->render('develop', array(
            'view' => 'menu_option',
            'data' => $data
        ));
        $navidation_html = $this->getNavigation($config, true);
        print_r(json_encode(array('result' => !empty($data), 'data' => $new_option, 'nav' => $navidation_html)));
    }

    protected function updateMenuOption($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'MenuManager', $data);
        $navidation_html = $this->getNavigation($config, true);
        print_r(json_encode(array('result' => !empty($data), 'data' => $data, 'nav' => $navidation_html)));
    }

    protected function deleteMenuOption($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'MenuManager', $data);
        $navidation_html = $this->getNavigation($config, true);
        print_r(json_encode(array('result' => !empty($data), 'data' => $data, 'nav' => $navidation_html)));
    }




    protected function addCategory($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'CategoryManager', $data);
        $new_option = $this->render('develop', array(
            'view' => 'rubric',
            'data' => $data
        ));
        print_r(json_encode(array('result' => !empty($data), 'data' => $new_option)));
    }

    protected function updateCategory($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'CategoryManager', $data);
        print_r(json_encode(array('result' => !empty($data), 'data' => $data)));
    }

    protected function deleteCategory($config, $data)
    {
        $data = $this->executeModel($config, 'develop', 'CategoryManager', $data);
        print_r(json_encode(array('result' => !empty($data), 'data' => $data)));
    }

}