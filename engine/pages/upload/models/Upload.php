<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 14.02.2020
 * Time: 21:38
 */
class Upload extends main_Model
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
        $src = strip_tags(strval($this->arguments['url']));
        $filename = time() .  rand(0, 100000) . '.jpg';
        $path = '/assets/img/fullsize/' . $filename;
        $path_preview = '/assets/img/preview/' . $filename;
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . $path, file_get_contents($src));


        if($this->arguments['import']){
            $file = getimagesize($_SERVER["DOCUMENT_ROOT"] . $path);
            $ext = explode('/', $file['mime']);
            self::createPreview($_SERVER["DOCUMENT_ROOT"] . $path, $_SERVER["DOCUMENT_ROOT"] . $path_preview, $ext[1]);

            $this->data = array(
                'result' => "success",
                'src' => $path_preview,
                'file' => "http://" . $_SERVER['SERVER_NAME'] . $path,
            );
        }else{
            if (filesize($_SERVER["DOCUMENT_ROOT"] . $path) > 0 && is_array(getimagesize($_SERVER["DOCUMENT_ROOT"] . $path))) {
                $file = getimagesize($_SERVER["DOCUMENT_ROOT"] . $path);
                $ext = explode('/', $file['mime']);
                self::createPreview($_SERVER["DOCUMENT_ROOT"] . $path, $_SERVER["DOCUMENT_ROOT"] . $path_preview, $ext[1]);
                sleep(.4);
                $this->data = array(
                    'result' => "success",
                    'src' => $path_preview,
                    'file' => "http://" . $_SERVER['SERVER_NAME'] . $path,
                );
            } else {
                unlink($path);
                $this->data = array(
                    'result' => "alert"
                );
            }
        }




    }


}