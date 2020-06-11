<?php

require_once __DIR__ . '/../engine/config.php';
require_once __DIR__ . '/../engine/main/main_Controller.php';
require_once __DIR__ . '/../engine/main/main_Model.php';
require_once __DIR__ . '/../engine/main/db.php';

session_start();

$query = explode('/', $_GET['query']);
$query = array_diff($query, array(0, null));


$page = !empty($query) ? $query[0] : 'index';
$_GET['page'] = $query[0] ? $query[0] : 'index';

$controller = $page . '_Controller';
$controller_path = __DIR__ . '/../engine/pages/' . $page . '/' . $controller . '.php';

if (file_exists($controller_path)) {
    $action = strval($_GET['action']) ? strval($_GET['action']) : 'get_page';
    require_once $controller_path;
    $object = new $controller($config['db'], $page);

    if(!empty($_FILES)){
        require_once 'uploader.php';
        exit();
    }

    if(empty($_POST)){
        $object->$action($config['db'], $query);
    }
    else{
        $object->ajax($config['db'], $_POST);
    }

} else {
    header('Location:/');
    //exit("Ошибка запроса");
}