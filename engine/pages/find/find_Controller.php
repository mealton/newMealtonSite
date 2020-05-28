<?php


class find_Controller extends main_Controller
{

    public function get_page($config, $query = array())
    {
        $title = count($query) > 1 ? urldecode($query[1]) : 'Результаты поиска';
        $data = $this->executeModel($config, 'find', 'get_Content', $query);

        $pagination = array();
        $pages = $data[0]['pages'];
        $page = 1;
        $current = count($query) > 2 ? end($query) : 1;
        while($page <= $pages){
            $pagination[] = array(
                'page' => $page++,
                'current' => $current,
                'content' => $query[0] . '/' . $query[1]
            );
        }

        $this->executeView('index', array(
            array('view' => 'get_title', 'data' => array('title' => $title, 'description' => 'Страничка пользователя'), 'container' => 'title'),
            array('view' => 'publication', 'data' => $data , 'container' => 'public-container'),
            array('view' => 'pagination', 'data' => count($pagination) > 1 ? $pagination : array(), 'container' => 'pagination')
        ));
    }

    protected function search($config, $data)
    {
        $search = $data['value'];
        $data = $this->executeModel($config, 'index', 'Search', $data);
        if(is_array($data)){
            foreach ($data as $k => $row){
                $data[$k]['long_title'] = $this->mb_str_replace( mb_strtolower($search), "<mark>" . addslashes($search) . "</mark>", mb_strtolower($data[$k]['long_title']));
            }
        }
        print_r(json_encode(array(
            'data' => $data,
            'search' => $search,
            'html' => $this->render('index', array(
                'view' => 'search-option',
                'data' => $data
            )),
        )));
    }


    private function mb_str_replace($search, $replace, $string)
    {
        $charset = mb_detect_encoding($string);
        $unicodeString = iconv($charset, "UTF-8", $string);
        return str_replace($search, $replace, $unicodeString);
    }
}