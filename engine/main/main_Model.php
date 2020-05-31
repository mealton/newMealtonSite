<?php


abstract class main_Model
{
    protected $db;

    protected $limit = 20;

    public function __construct($config = array())
    {
        date_default_timezone_set('Europe/Moscow');
        DB::getInstance()->Connect($config["login"], $config["password"], $config["database"]);
    }

    public static function pre($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }


    abstract function set($arguments = array());

    abstract function get();

    abstract function execute();


    public function updateLinkName()
    {
        $sql = "SELECT `id`, `title` FROM `posts`";
        $rows = db::getInstance()->Select($sql);
        foreach ($rows as $row) {
            $url_name = $this->translit($row['title']);
            $id = $row['id'];
            $sql = 'UPDATE `posts` SET `url_name` = "' . $url_name . '" WHERE `id` = ' . $id;
            db::getInstance()->Query($sql);
        }
    }


    public static function translit($string)
    {
        $abc = array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'j',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ъ' => '\'',
            'ы' => 'y',
            'ь' => '\'',
            'э' => 'e',
            'ю' => 'ju',
            'я' => 'ja'
        );

        $translit = '';
        $string = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($string as $char) {
            if (preg_match('/\p{Cyrillic}/ui', $char)) {
                $translit .= $abc[mb_strtolower($char)];
            } else {
                $translit .= mb_strtolower($char);
            }
        }
        $translit = preg_replace('/\s+/', '_', $translit);
        $translit = preg_replace('/_{2,}/', '_', $translit);
        return preg_replace('/[^a-z\d+_\']/', "", $translit);
    }

    public static function getTagsString($tags)
    {
        $string = strpos($_GET['query'], 'tags/') ?  '<a href="/category#hashtags" class="tag-link">все теги</a>&nbsp;' : '';
        foreach (explode(",", $tags) as $tag){
                if(trim($tag)){
                    if(in_array(trim(mb_strtolower($tag)), explode('/', $_GET['query']))){
                        $string .= '<a class="tag-link active-tag">' . trim(mb_strtolower($tag)) . '</a>&nbsp;';
                    }else{
                        $string .= '<a href="/hashtags/' . trim(mb_strtolower($tag)) . '" class="tag-link">' . trim(mb_strtolower($tag)) . '</a>&nbsp;';
                    }
                }
        }
        return $string;
    }


    public static function getImportLink($link)
    {
        preg_match('/:\/\/+[^\/]+/', $link, $matches);
        return preg_replace('/:\/\/+(www.)?/', '', $matches[0]);
    }


    public static function createPreview ($input,$output, $type) {
        $w = 300;
        $q = 200;
        $f = $input;
        switch ($type){
            case 'png':
                $src = imagecreatefrompng ($f);
                break;
            case 'gif':
                $src = imagecreatefromgif ($f);
                break;
            default:
                $src = imagecreatefromjpeg($f);
        }

        $w_src = imagesx($src);
        $h_src= imagesy($src);

        // получение ширины и высоты изображения в пикселях
        $ratio = $w_src/$w;
        $w_dest = round($w_src/$ratio);
        $h_dest = round($h_src/$ratio);

        // получение координат для построения нового изображения необходимой нам ширины
        $dest = imagecreatetruecolor($w_dest,$h_dest);
        // функция  imagecreatetruecolor пустое полноцветное изображение размерами x_size и y_size.
        // Созданное изображение имеет черный фон.
        imagecopyresized($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
        // Функция imagecopyresized копирует прямоугольные области с одного изображения на другое
        // вывод картинки и очистка памяти
        imagejpeg($dest,$output,$q);
        imagedestroy($dest);
        imagedestroy($src);

    }


}