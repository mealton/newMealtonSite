<?php
/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 15.02.2020
 * Time: 11:14
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/../engine/main/main_Model.php';

$response = true;
$files = array();


for($i = 0; $i < count($_FILES['file']['tmp_name']); $i++){
    $filename = rand(1000, 100000) . '.jpg';
    $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/fullsize/' . $filename;
    $path_preview = $_SERVER["DOCUMENT_ROOT"] . '/assets/img/preview/' . $filename;
    $result = move_uploaded_file($_FILES['file']['tmp_name'][$i], $path);

    //Миниатюрка
    $file = getimagesize($path);
    $ext = explode('/', $file['mime']);
    main_Model::createPreview($path, $path_preview, $ext[1]);

    array_push($files, '/assets/img/preview/' . $filename);

    if (!$result) {
        $response = false;
    }
}

print_r(json_encode(array(
    'result' => $response ? 'success' : 'alert',
    'files' => $files
)));