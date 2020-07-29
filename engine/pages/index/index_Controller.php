<?php


class index_Controller extends main_Controller
{

    public function get_page($config, $query = array())
    {
        $data = $this->executeModel($config, 'index', 'get_Content', $query);

        //main_Model::pre($data);

        foreach ($data as $k => $row){
            $data[$k]['hashtags'] = !empty($data[$k]['hashtags']) ? $this->render('public', array(
                'view' => 'hashtag',
                'data' => $row['hashtags-counter']
            )) : '';
        }

        //main_Model::pre($data);

        $pagination = array();
        $pages = $data[0]['pages'];
        $page = 1;
        $current = count($query) > 1 ? end($query) : 1;
        while($page <= $pages){
            $pagination[] = array(
                'page' => $page++,
                'current' => $current,
                'content' => $query[0] ? $query[0] : 'index'
            );
        }

        $this->executeView('index', array(
            array('view' => 'get_title', 'data' => array('title' => 'Главная страница', 'description' => 'Страничка пользователя'), 'container' => 'title'),
            array('view' => 'publication', 'data' => $data, 'container' => 'public-container'),
            array('view' => 'pagination', 'data' => count($pagination) > 1 ? $pagination : array(), 'container' => 'pagination')
        ));
    }

    protected function appender($config, $data)
    {
        $data = $this->executeModel($config, 'index', 'get_Content', $data);
        print_r(json_encode(array(
            'result' => 'метод appender',
            'data' => $data['data'],
            'html' => $this->render('index', array(
                'view' => 'publication',
                'data' => $data['data']
            )),
            'sql' => $data['sql']
        )));
    }
}