<?php


class hashtags_Controller extends main_Controller
{

    public function get_page($config, $query = array())
    {
        session_start();
        $data = $this->executeModel($config, 'hashtags', 'get_Content', $query);
        $hashtag = $data[0]['hashtag'];
        $hashtag = mb_strtoupper(mb_substr($hashtag, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($hashtag, 1, mb_strlen($hashtag, 'UTF-8'), 'UTF-8');

        foreach ($data as $k => $row){
            $data[$k]['hashtags'] = !empty($data[$k]['hashtags']) ? $this->render('public', array(
                'view' => 'hashtag',
                'data' => $row['hashtags-counter']
            )) : '';
        }

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
            array('view' => 'get_title', 'data' => array('title' => '#' . $hashtag, 'description' => 'Страничка пользователя'), 'container' => 'title'),
            array('view' => 'publication', 'data' => $data, 'container' => 'public-container'),
            array('view' => 'pagination', 'data' => count($pagination) > 1 ? $pagination : array(), 'container' => 'pagination')
        ));
    }
}