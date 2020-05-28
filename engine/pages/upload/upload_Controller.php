<?php

/**
 * Created by PhpStorm.
 * User: Юрчик
 * Date: 08.03.2020
 * Time: 17:01
 */
class upload_Controller extends main_Controller
{

    protected function uploadUrl($config, $data)
    {
        $data = $this->executeModel($config, 'upload', 'Upload', $data);
        print_r(json_encode($data));
    }

    protected function deleteImg($config, $data)
    {
        $src = $_SERVER['DOCUMENT_ROOT'] . $data['src'];
        $result = array(
            'result' => true,
            'preview' => $src,
            'fullsize' => false
        );
        if (file_exists($src)) {
            unlink($src);
        } else {
            $result['result'] = false;
        }
        $src = str_replace('preview', 'fullsize', $src);
        $result['fullsize'] = $src;
        if (file_exists($src)) {
            unlink($src);
        } else {
            $result['result'] = false;
        }
        print_r(json_encode($result));
    }


    protected function import($config, $data)
    {
        session_start();
        include_once "simple_html_dom.php";

        $url = $data['url'];
        $category = strval($data['category']);

        $html = $this->curl_get($url) ? $this->curl_get($url) : file_get_contents($url);
        $dom = str_get_html($html);

        $metaTags = get_meta_tags($url);
        $desciption = $metaTags['description'];
        $short_title = $metaTags['title'];
        $h1 = $dom->find("h1");

        $fields = array();


        if (preg_match('/fishki.net/', $url)) {

            $tags = $dom->find(".tags");
            $content = $dom->find(".post_content_inner");
            $elements = $content[0] ?
                $content[0]->find("img, p, h2 h3, h4, h5, h6, span, div") :
                $dom->find("img, p, h2 h3, h4, h5, h6, span, div");


            $tagsArray = array();


            if ($tags[0]) {
                $tags = is_array($tags[0]->find("a")) ? $tags[0]->find("a") : array();

                foreach ($tags as $tag) {
                    array_push($tagsArray, $tag->plaintext);
                }
            }

            foreach ($elements as $element) {

                if ($element->src) {
                    $field = 'image';
                    $data = $this->executeModel($config, 'upload', 'Upload', array(
                            'url' => 'https:' . $element->src,
                            'import' => true
                        )
                    );
                    $value = $data['src'];
                } else {
                    $field = 'text';
                    $value = $element->plaintext;
                }

                if ($value && !preg_match('/Поделиться|Поделитесь с друзьями/', $value)) {
                    $fields[] = array(
                        'style' => '',
                        'field' => $field,
                        'value' => $value
                    );
                }
            }


            $_SESSION['publication'] = array(
                'alias' => trim(main_Model::translit(preg_replace('/\(\d+&nbsp;фото\)/', '', trim($h1[0]->plaintext))), '_'),
                'short_title' => preg_replace('/\(\d+&nbsp;фото\)/', '', trim($h1[0]->plaintext)),
                'long_title' => preg_replace('/\(\d+&nbsp;фото\)/', '', trim($h1[0]->plaintext)),
                'description' => $desciption,
                'user_id' => $_SESSION['userId'],
                'hashtags' => implode(",", $tagsArray),
                'imported' => $url,
                'category' => $category,
                "comment" => 'Импортированно на другого сайта',
                'fields' => $fields
            );
        } else {

            $elements = $dom->find("img, p, h2 h3, h4, h5, h6, span, div");

            foreach ($elements as $element) {

                if ($element->src) {
                    $field = 'image';
                    $data = $this->executeModel($config, 'upload', 'Upload', array(
                            'url' => $element->src,
                            'import' => true
                        )
                    );
                    $value = $data['src'];
                } else {
                    $field = 'text';
                    $value = $element->plaintext;
                }

                if ($value) {
                    $fields[] = array(
                        'field' => $field,
                        'value' => $value
                    );
                }
            }

            $_SESSION['publication'] = array(
                'alias' => main_Model::translit($h1[0]->plaintext),
                'short_title' => $h1[0]->plaintext ? $h1[0]->plaintext : $metaTags['title'],
                'long_title' => $h1[0]->plaintext ? $h1[0]->plaintext : $metaTags['title'],
                'description' => $desciption,
                'user_id' => $_SESSION['userId'],
                'hashtags' => implode(",", $tagsArray),
                'imported' => $url,
                'category' => $category,
                "comment" => 'Импортированно на другого сайта',
                'fields' => $fields
            );
        }


        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));

        print_r(json_encode(array('publication' => $publication, 'data' => $_SESSION['publication'])));
    }

    //Импорт публикации
    private function curl_get($url, $referer = 'https://www.google.com')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; ry:38.0) Gecko/20190101 Firefox/38.0");
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}