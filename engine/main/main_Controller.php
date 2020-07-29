<?php


class main_Controller
{

    public static $navigation;
    public static $sidebar;
    public static $sidebarCounter;
    public static $profile_li;
    public static $css;
    public static $js;

    public static $categories_assoc;
    public static $current_public_category;

    protected $userId;

    public function __construct($config, $page)
    {
        session_start();

        $public_id = intval(current(explode("::", explode("/", $_GET['query'])[1])));

        self::$current_public_category = $page == 'public' && $public_id ?
            $this->executeModel($config, 'category', 'get_CurrentCategoryName', $public_id) :
            false;

        if ($_COOKIE['username'] && $_COOKIE['password'] && !$_SESSION['auth']) {
            $this->auth($config, array(
                'username' => $_COOKIE['username'],
                'password' => $_COOKIE['password'],
                'cookie' => true
            ));
        }

        self::$categories_assoc = $this->executeModel($config, 'category', 'get_CategoryName');

        //main_Model::pre(self::$categories_assoc);
        
        if (!in_array($page, array('index')))
            unset($_SESSION['page']);

        $this->getNavigation($config);
        $this->getSidebar($config);
        $this->getScripts($page);
    }

    protected function getNavigation($config, $rerender = false)
    {
        $navigation = $this->executeModel($config, 'navigation', 'getNav');

        self::$profile_li = $this->render('navigation', array(
            'view' => 'profile_li',
            'data' => array()
        ));

        $navigation['category']['categories'] = $this->executeView('navigation', array(
            array('view' => 'category_li', 'data' => $navigation['category']['categories'], 'container' => 'categories_container')
        ), true);

        foreach ($navigation['options'] as $k => $v){
            $navigation[$v['menu_option_url']] = array(
                'menu_option' => $v['menu_option'],
                'menu_option_url' => $v['menu_option_url']
            );
        }

        unset($navigation['options']);

        //main_Model::pre($navigation);

        if ($rerender) {
            return $this->render('navigation', array(
                'view' => 'navbar_li',
                'data' => array_values($navigation)
            )) .
            $this->render('navigation', array(
                'view' => 'profile_li',
                'data' => array(array())
            ));
        } else {
            self::$navigation = $this->executeView('navigation', array(
                array('view' => 'navbar_li', 'data' => array_values($navigation), 'container' => 'navbar')
            ), true);
        }

        return true;
    }


    private function getSidebar($config)
    {
        $sidebarData = $this->executeModel($config, 'sidebar', 'getSidebar');
        self::$sidebarCounter = $sidebarData[0]['counter'];
        self::$sidebar = $this->executeView('sidebar', array(
            array('view' => 'sidebar-public', 'data' => $sidebarData, 'container' => 'sidebar')
        ), true);
    }

    private function getScripts($page)
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/pages/' . $page;
        $css = is_dir($path . '/css/') ? array_slice(scandir($path . '/css/'), 2) : array();
        $links = '';
        foreach ($css as $link) {
            $links .= '<link href="/assets/pages/' . $page . '/css/' . $link . '" rel="stylesheet">';
        }
        self::$css = $links;
        $js = is_dir($path . '/js/') ? array_slice(scandir($path . '/js/'), 2) : array();
        $scripts = '';
        foreach ($js as $script) {
            $scripts .= '<script src="/assets/pages/' . $page . '/js/' . $script . '"></script>';
        }
        self::$js = $scripts;
    }

    public function executeModel($config, $page, $model, $arguments = array())
    {
        $model_path = __DIR__ . '/../pages/' . $page . '/models/' . $model . '.php';
        if (file_exists($model_path)) {
            require_once $model_path;
            $model = new $model($config);
            $model->set($arguments);
            $model->execute();
            return $model->get();
        } else {
            return false;
        }
    }

    public function executeView($page, $data = array(), $return = false)
    {
        $content = '';
        foreach ($data as $array) {
            ob_start();
            $container = $array['container'] && file_exists(__DIR__ . '/../pages/' . $page . '/containers/' . $array['container'] . '.php') ? $array['container'] : 'basic';
            $container_path = __DIR__ . '/../pages/' . $page . '/containers/' . $container . '.php';


            $html = empty($array['data']) ? "" : $this->render($page, $array);
            if (file_exists($container_path)) {
                include $container_path;
                $content .= ob_get_contents();
                ob_get_clean();
            } else {
                $content .= $html;
            }


        }
        if ($return) {
            return $content;
        } else {
            $title = $data[0]['data']['title'] ? $data[0]['data']['title'] : 'Титульник по умолчанию';
            $description = $data[0]['data']['description'];
            $label = $this->getLabel($data) ? $this->getLabel($data) : '/assets/img/header-bg.jpg';
            include_once __DIR__ . '/main_View.php';
        }
        return true;
    }

    private function getLabel($data)
    {
        $images = array();
        foreach ($data as $item) {
            foreach ($item['data'] as $value) {
                if ($value['tag_category'] == 'image' && $value['content']) {
                    $images[] = $value['content'];
                }
            }
        }
        $random = rand(0, count($images) - 1);
        return $images[$random];
    }

    public function render($page, $data = array())
    {
        ob_start();
        $view = $data['view'];
        $data = !$this->isNumeric($data['data']) ? array($data['data']) : $data['data'];
        $view_path = __DIR__ . '/../pages/' . $page . '/views/' . $view . '.php';

        if (file_exists($view_path)) {
            if (empty($data))
                return '';
            foreach ($data as $k => $row) {
                $row = is_array($row) ? $row : array($k => $row);
                foreach ($row as $key => $value) {
                    $$key = $value;
                }
                include $view_path;
            }
        }
        $content = ob_get_contents();

        ob_get_clean();
        return $content;
    }


    private function isNumeric($arr = array())
    {
        if (empty($arr))
            return false;
        foreach (array_keys($arr) as $key) {
            if (!is_integer($key)) {
                return false;
            }
        }
        return true;
    }

    /*ДЛЯ AJAX*/
    public function ajax($config, $data = array())
    {
        if (!method_exists($this, $data['action'])) {
            print_r(json_encode("Ошибка!!!"));
            return false;
        }
        $this->$data['action']($config, $data);
        return true;
    }


    private function sessionConfig($config, $data)
    {
        session_start();
        $_SESSION['config'] = array(
            'content' => $data['content'],
            'id' => $data['id'],
            'menu' => $data['menu']
        );
        print_r(json_encode(array('result' => '***', 'config' => $_SESSION['config'])));

    }


    private function auth($config, $data)
    {
        session_start();
        $userdata = $this->executeModel($config, 'auth', 'Auth', $data);
        if (!empty($userdata) && count($userdata) == 1) {
            $_SESSION['auth'] = true;
            $_SESSION['admin'] = $userdata[0]['status'] === "admin";
            $_SESSION['username'] = $userdata[0]['name'];
            $_SESSION['login'] = $userdata[0]['username'];
            $_SESSION['userId'] = $userdata[0]['id'];
            setcookie("username", $userdata[0]['username'], time() + 30 * 24 * 3600, "/");
            setcookie("password", $userdata[0]['password'], time() + 30 * 24 * 3600, "/");
            $result = 'true';
        } else {
            $result = 'false';
        }
        //$navidation_html = $this->reRenderNav($config);
        if (!$data['cookie'])
            print_r(json_encode(array('result' => $result, 'navidation_html' => $this->getNavigation($config, true))));
    }

    /*public function reRenderNav($config)
    {
        $navigation_data = $this->executeModel($config, 'navigation', 'getNav');
        return
            $this->render('navigation', array(
                'view' => 'navbar_li',
                'data' => $navigation_data
            )) .
            $this->render('navigation', array(
                'view' => 'profile_li',
                'data' => array(array())
            ));
    }*/

    private function logout($config, $data)
    {
        session_start();
        $_SESSION = array();
        setcookie("username", "", time() - 30 * 24 * 3600, "/");
        setcookie("password", "", time() - 30 * 24 * 3600, "/");
        $result = !$_SESSION['auth'] ? 'true' : 'false';
        print_r(json_encode(array('result' => $result, 'navidation_html' => $this->getNavigation($config, true))));

    }

    public static function dateRusFormat($date)
    {
        $russian_months = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
        $date_parse = date_parse($date);
        return $date_parse['day'] . ' ' . $russian_months[$date_parse['month'] - 1] . ' ' . $date_parse['year'];
    }

    public static function dateTimeRusFormat($date)
    {
        $russian_months = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
        $date_parse = date_parse($date);
        return $date_parse['day'] . ' ' . $russian_months[$date_parse['month'] - 1] . ' ' . $date_parse['year'] . ' в ' . $date_parse['hour'] . ':' . $date_parse['minute'];
    }

    public static function getEnding($number, $endings)
    {
        if ($number % 10 == 1 && $number % 100 != 11) {
            return $endings[0];
        } elseif (in_array($number % 10, array(2, 3, 4)) && !in_array($number % 100, array(12, 13, 14))) {
            return $endings[1];
        } else {
            return $endings[2];
        }
    }

    public static function getPeriod($date)
    {
        $diff = abs(strtotime(date('Y-m-d')) - strtotime($date));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        $result = '';

        if ($years) {
            $result = $years . ' ' . self::getEnding($years, array('год', 'года', 'лет')) . ' ' .
                ($months ? $months . ' ' . self::getEnding($months, array('месяц', 'месяца', 'месяцев')) : '');
        } elseif (!$years && $months) {
            $result = $months . ' ' . self::getEnding($months, array('месяц', 'месяца', 'месяцев')) . ' ' .
                $days . ' ' . self::getEnding($days, array('день', 'дня', 'дней'));
        } elseif (!$years && !$months && $days) {
            $result = $days . ' ' . self::getEnding($days, array('день', 'дня', 'дней'));
        } else {
            $result = ' с сегодняшнего дня';
        }

        return $result;
    }


    public static function mediaCounter($count_img, $count_video)
    {
        $result = '';
        if ($count_img && !$count_video) {
            $result = '(' . $count_img . ' фото)';
        } elseif (!$count_img && $count_video) {
            $result = '(' . $count_video . ' видео)';
        } elseif ($count_img && $count_video) {
            $result = '(' . $count_img . ' фото и ' . $count_video . ' видео)';
        }
        return $result;
    }


    public static function get_youtube_title($ref)
    {
        $json = file_get_contents('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' . $ref . '&format=json'); //get JSON video details
        $details = json_decode($json, true); //parse the JSON into an array
        return $details['title']; //return the video title
    }


    protected function search($config, $data)
    {
        $search = $data['value'];
        $data = $this->executeModel($config, 'index', 'Search', $data);
        /*if (is_array($data)) {
            foreach ($data as $k => $row) {
                $data[$k]['long_title'] = preg_replace('/'. $this->mb_encode_utf8($search) .'/i', '<mark>$0</mark>', $this->mb_encode_utf8($data[$k]['long_title']));
                //$data[$k]['long_title'] = $this->mb_str_replace(mb_strtolower($search), "<mark>" . addslashes($search) . "</mark>", mb_strtolower($data[$k]['long_title']));
            }
        }*/
        print_r(json_encode(array(
            'data' => $data,
            'search' => $search,
            'html' => $this->render('index', array(
                'view' => 'search-option',
                'data' => $data
            )),
        )));
    }


    private function mb_encode_utf8($string)
    {
        $charset = mb_detect_encoding($string);
        return iconv($charset, "UTF-8", $string);
        //return str_replace($search, $replace, $unicodeString);
    }


    private function mb_str_replace($search, $replace, $string)
    {
        $charset = mb_detect_encoding($string);
        $unicodeString = iconv($charset, "UTF-8", $string);
        return str_replace($search, $replace, $unicodeString);
    }


    protected function scroll($config, $data)
    {
        $data = $this->executeModel($config, 'sidebar', 'getSidebar', $data);
        print_r(json_encode(array(
            'data' => $data,
            'html' => $this->render('sidebar', array(
                'view' => 'sidebar-public',
                'data' => $data
            )),
        )));
    }

    public static function textConverter ($text)
    {
        return preg_replace(
            array(
                "/(http(s)?:\/\/)([a-z0-9.'-]+)(.*)(jpg|png|gif)(.*)/",
                "/\s(http(s)?:\/\/)([a-z0-9.'-]+)(.*)/",
                "/\n/"
            ),
            array(
                "<img src='$1$3$4$5' class='comment-image' width='300' /><br>",
                "<a href='$0' target='_blank'>$3</a>",
                "<br>\n"
            ),
            $text);
    }

}