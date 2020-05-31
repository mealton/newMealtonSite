<?php


class category_Controller extends main_Controller
{

    public static $category;

    public function get_page($config, $query = array())
    {
        session_start();
        $data = count($query) > 1 ?
            $this->executeModel($config, 'category', 'get_Content', $query) :
            $this->executeModel($config, 'category', 'get_Categories', $query);
        $category = count($query) > 1 ? $data[0]['rubric_name'] : 'Все категории';
        $_SESSION['category'] = $category === 'Все категории' ? 'категория' : $category;

        if (count($query) > 1) {

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
                array('view' => 'get_title', 'data' => array('title' => 'Категория: "' . addslashes($category) . '"', 'description' => 'Страничка пользователя'), 'container' => 'title'),
                array('view' => 'publication', 'data' => $data, 'container' => 'public-container'),
                array('view' => 'pagination', 'data' => count($pagination) > 1 ? $pagination : array(), 'container' => 'pagination')
            ));
        } else {

            $data_html = array();

            if (is_array($data)) {

                $i = 1;
                foreach ($data as $k => $row) {
                    foreach ($row['publics'] as $j => $public){
                        $data[$k]['publics'][$j]['number'] = $i++;
                    }
                }

                //main_Model::pre($data);

                foreach ($data as $row) {
                    $data_html[] = array(
                        'category' => $row['category'],
                        'category_alias' => $row['category_alias'],
                        'publics' => $this->render('category', array(
                            'view' => 'category',
                            'data' => $row['publics']
                        ))
                    );
                }
            }

            $tags = $this->executeModel($config, 'category', 'get_Tags', $query);
            $tags_html = array(
                'category' => 'Хэштеги<span id="hashtags"></span>',
                'category_alias' => '',
                'publics' => ''
            );


            $tags_html['publics'] = '<div class="heshtags">' . $this->render('category', array(
                'view' => 'tag',
                'data' => $tags
            )) . '</div>';

            array_push($data_html, $tags_html);

            //main_Model::pre($data_html);
            $this->executeView('index', array(
                array('view' => 'get_title', 'data' => array('title' => $category, 'description' => 'Страничка пользователя'), 'container' => 'title'),
                array('view' => 'categoryItem', 'data' => $data_html, 'container' => 'categories-container'),
                array('view' => 'get_footer')
            ));
        }

    }

    protected function appender($config, $data)
    {
        $data = $this->executeModel($config, 'category', 'get_Content', $data);
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