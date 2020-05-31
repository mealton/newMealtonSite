<?php


class profile_Controller extends main_Controller
{

    public function __construct($config, $page)
    {
        session_start();

        if(!$_SESSION['admin']){
            header('Location:/');
        }

        parent::__construct($config, $page);
    }

    public function get_page($config, $query)
    {
        session_start();
        $arguments['userId'] = $_SESSION['userId'];

        $query = explode('/', $_GET['query']);

        $showOne = false;

        if ($query[1] && preg_match('/^\d+::/', $query[1])) {
            $id = current(explode('::', $query[1]));
            if (intval($id)) {
                $showOne = true;
                $arguments['publicId'] = $id;
            }
        }else{
            $arguments['page'] = intval($query[1]) ? intval($query[1]) : 1;
        }

        $data = $this->executeModel($config, 'profile', 'get_Content', $arguments);

        //main_Model::pre($data);

        if ($showOne) {

            if ($_SESSION['publication']['post_id'] != $data['publication'][0]['post_id']) {

                $_SESSION['publication']['post_id'] = $data['publication'][0]['post_id'];
                $_SESSION['publication']['short_title'] = $data['publication'][0]['short_title'];
                $_SESSION['publication']['alias'] = $data['publication'][0]['alias'];
                $_SESSION['publication']['image_default'] = $data['publication'][0]['image_default'];
                $_SESSION['publication']['long_title'] = $data['publication'][0]['long_title'];
                $_SESSION['publication']['description'] = $data['publication'][0]['description'];
                $_SESSION['publication']['user_id'] = $data['publication'][0]['user_id'];
                $_SESSION['publication']['hashtags'] = $data['publication'][0]['hashtags'];
                $_SESSION['publication']['imported'] = $data['publication'][0]['imported'];

                $_SESSION['publication']['category'] = $data['publication'][0]['category'];

                $categoriesHTML = $this->render('profile', array(
                    'view' => 'category_option',
                    'data' => $data['categories']
                ));

                $_SESSION['publication']['categories'] = $categoriesHTML;
                $_SESSION['publication']['fields'] = array();
                if (is_array($data['publication'])) {
                    foreach ($data['publication'] as $item) {
                        $_SESSION['publication']['fields'][] = array(
                            'style' => $item['style'],
                            'field' => $item['tag_category'],
                            'value' => $item['content']
                        );
                    }
                }
            }

            $publication = $this->render('profile', array(
                'view' => 'publication_item',
                'data' => $_SESSION['publication']['fields']
            ));

            $title = $_SESSION['publication']['long_title'] ? $_SESSION['publication']['long_title'] : $_SESSION['publication']['short_title'];

            $_SESSION['publication']['publication'] = $publication;


            $this->executeView('profile', array(
                array('view' => 'get_title', 'data' => array('title' => $title, 'description' => 'Страничка пользователя'), 'container' => 'title'),
                array('view' => 'editPublic', 'data' => $_SESSION['publication'], 'container' => 'userdata')
            ));

        } else {
            $title = $data['userdata'][0]['name'] ? $data['userdata'][0]['name'] : $data['userdata'][0]['usernname'];

            $getUserPublications = $this->render('profile', array(
                'view' => 'userPubicItem',
                'data' => $data['getUserPublications']
            ));

           // unset($_SESSION['publication']['fields']['all']);

            $publication = $this->render('profile', array(
                'view' => 'publication_item',
                'data' => $_SESSION['publication']['fields']
            ));

            $categoriesHTML = $this->render('profile', array(
                'view' => 'category_option',
                'data' => $data['categories']
            ));

            $data['userdata'][0]['categories'] = $categoriesHTML;
            $data['userdata'][0]['publication'] = $publication;

            $data['userdata'][0]['getUserPublications'] = $getUserPublications;

            $data['userdata'][0]['short_title'] = $_SESSION['publication']['short_title'];
            $data['userdata'][0]['long_title'] = $_SESSION['publication']['long_title'];
            $data['userdata'][0]['description'] = $_SESSION['publication']['description'];
            $data['userdata'][0]['alias'] = $_SESSION['publication']['alias'];
            $data['userdata'][0]['imported'] = $_SESSION['publication']['imported'];
            $data['userdata'][0]['hashtags'] = $_SESSION['publication']['hashtags'];

            $_SESSION['publication']['user_id'] = $_SESSION['userId'];

            $pagination = array();
            $pages = $data['getUserPublications'][0]['pages'];
            $page = 1;
            $current = count($query) > 1 ? $query[1] : 1;
            while($page <= $pages){
                $pagination[] = array(
                    'page' => $page++,
                    'current' => $current ? $current : 1,
                    'content' => $query[0]
                );
            }
            
            $this->executeView('profile', array(
                array('view' => 'get_title', 'data' => array('title' => $title, 'description' => 'Страничка пользователя'), 'container' => 'title'),
                array('view' => 'userdata', 'data' => $data['userdata'], 'container' => 'userdata'),
                array('view' => 'pagination', 'data' => count($pagination) > 1 ? $pagination : array(), 'container' => 'pagination')
            ));
        }


    }


    protected function edit($config, $data)
    {
        $input = $this->render('profile', array(
            'view' => 'update_input',
            'data' => array($data)
        ));
        print_r(json_encode(array('input' => $input)));
    }

    protected function updateUser($config, $data)
    {
        $data = $this->executeModel($config, 'profile', 'ProfileManager', $data);
        print_r(json_encode(array('result' => !empty($data), 'data' => $data)));
    }

    protected function getField($config, $data)
    {
        $field = $this->render('profile', array(
            'view' => $data['view'],
            'data' => array()
        ));
        print_r(json_encode(array('field' => $field)));
    }


    protected function addPublicData($config, $data)
    {

        session_start();

        if ($data['insertAfter']) {


            if (is_array($data['value'])) {
                foreach ($data['value'] as $value) {
                    array_splice(
                        $_SESSION['publication']['fields'],
                        $data['insertAfter'],
                        0,
                        array(
                            array(
                                'style' => '',
                                'field' => $data['field'],
                                'value' => $value
                            )
                        )
                    );
                }
            } else {
                array_splice(
                    $_SESSION['publication']['fields'],
                    $data['insertAfter'],
                    0,
                    array(
                        array(
                            'style' => '',
                            'field' => $data['field'],
                            'value' => $data['value']
                        )
                    )
                );
            }


        } else {

            if (is_array($data['value'])) {
                foreach ($data['value'] as $value) {
                    $_SESSION['publication']['fields'][] = array(
                        'style' => '',
                        'field' => $data['field'],
                        'value' => $value
                    );
                }
            } else {
                $_SESSION['publication']['fields'][] = array(
                    'style' => '',
                    'field' => $data['field'],
                    'value' => $data['value']
                );
            }

        }

        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication)));
    }


    protected function updateItem($config, $data)
    {
        session_start();
        $_SESSION['publication']['fields'][$data['id']]['value'] = $data['value'];
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication)));
    }


    protected function removePublicItem($config, $data)
    {
        session_start();
        unset($_SESSION['publication']['fields'][$data['index']]);
        if ($_SESSION['publication']['fields'][$data['index']]['field'] == 'image' &&
            file_exists($_SERVER['DOCUMENT_ROOT'] . $_SESSION['publication']['fields'][$data['index']]['value'])) {
            unlink($_SESSION['publication']['fields'][$data['index']]['value']);
        }
        $_SESSION['publication']['fields'] = array_values($_SESSION['publication']['fields']);


        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication)));
    }

    protected function multiRemove($config, $data)
    {
        session_start();
        $ids = $data['ids'];
        foreach ($ids as $id){
            unset($_SESSION['publication']['fields'][$id]);

            if ($_SESSION['publication']['fields'][$id]['field'] == 'image' &&
                file_exists($_SERVER['DOCUMENT_ROOT'] . $_SESSION['publication']['fields'][$id]['value'])) {
                unlink($_SESSION['publication']['fields'][$id]['value']);
            }

        }
        $_SESSION['publication']['fields'] = array_values($_SESSION['publication']['fields']);
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication)));
    }

    protected function changeField($config, $data)
    {
        session_start();
        $_SESSION['publication']['fields'][$data['id']]['field'] = $data['value'];
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication)));
    }

    protected function setAllTextFields($config, $data)
    {
        session_start();

        foreach ($_SESSION['publication']['fields'] as $index => $field) {
            if (!in_array($field['field'], array('image', 'video'))) {
                $_SESSION['publication']['fields'][$index]['field'] = $data['value'];
            }
        }

        //$_SESSION['publication']['fields'][$data['id']]['field'] = $data['value'];
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication)));
    }

    protected function updateStyle($config, $data)
    {
        session_start();
        $_SESSION['publication']['fields'][$data['id']]['style'] = $data['style'];
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication)));
    }

    protected function updateStyleAll($config, $data)
    {
        session_start();
        foreach ($_SESSION['publication']['fields'] as $index => $field) {
            if (!in_array($field['field'], array('image', 'video'))) {
                $_SESSION['publication']['fields'][$index]['style'] = $data['style'];
            }
        }
        //$_SESSION['publication']['fields'][$data['id']]['style'] = $data['style'];
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication)));
    }

    protected function updatePublicTitles($config, $data)
    {
        session_start();
        $_SESSION['publication'][$data['field']] = $data['value'];
        if ($data['field'] == 'short_title') {
            $_SESSION['publication']['alias'] = main_Model::translit($data['value']);
        }
        print_r(json_encode(array('translit' => $_SESSION['publication']['alias'])));
    }

    protected function setPublicationCategory($config, $data)
    {
        session_start();
        $_SESSION['publication']['category'] = $data['value'];
        print_r(json_encode(array('category' => $_SESSION['publication']['category'])));
    }

    protected function setImageDefault($config, $data)
    {
        session_start();
        $_SESSION['publication']['image_default'] = $data['value'];
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication, 'image_default' => $_SESSION['publication']['image_default'])));
    }

    protected function replacePublicItem($config, $data)
    {
        session_start();
        $item = array($_SESSION['publication']['fields'][$data['idToReplace']]);
        unset($_SESSION['publication']['fields'][$data['idToReplace']]);
        array_splice($_SESSION['publication']['fields'], $data['idToInsertAfter'], 0, $item);
        $_SESSION['publication']['fields'] = array_values($_SESSION['publication']['fields']);
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        print_r(json_encode(array('publication' => $publication, 'data' => $_SESSION['publication']['fields'], 'item' => $item)));
    }

    protected function submitNewPost($config, $data)
    {
        session_start();
        //$_SESSION['publication']['user_id'] = $_SESSION['userId'];
        $result = $this->executeModel($config, 'profile', 'PublicManager', $_SESSION['publication']);
        if ($result) {
            $_SESSION['publication'] = array();
            $_SESSION['config'] = array(
                'content' => 'post',
                'id' => $result[0]['id']
            );
            //header('Location:' . $result['alias']);
        }
        print_r(json_encode(array('result' => $result)));
    }


    protected function updatePost($config, $data)
    {
        session_start();
        $result = $this->executeModel($config, 'profile', 'PublicUpdateManager', $_SESSION['publication']);
        if ($result)
            $_SESSION['publication'] = array();
        print_r(json_encode(array('result' => $result)));
    }


    protected function unpublished($config, $data)
    {
        $result = $this->executeModel($config, 'profile', 'easyUpdate', $data);
        if ($result) {
            $public_item = $this->render('profile', array(
                'view' => 'userPubicItem',
                'data' => $result
            ));
        } else {
            $public_item = false;
        }
        print_r(json_encode(array('result' => $result, 'public_item' => $public_item)));
    }


    protected function cancelPublication($config, $data)
    {
        session_start();
        foreach ($_SESSION['publication']['fields'] as $field) {
            if ($field['field'] == 'image' && file_exists($_SERVER['DOCUMENT_ROOT'] . $field['value'])) {
                unlink($field['value']);
            }
        }
        $_SESSION['publication']['field'] = array();
        $publication = $this->render('profile', array(
            'view' => 'publication_item',
            'data' => $_SESSION['publication']['fields']
        ));
        $_SESSION['publication'] = array();
        print_r(json_encode(array('publication' => empty($_SESSION['publication']['fields']) ? "" : $publication)));
    }

    protected function closeEdit($config, $data)
    {
        session_start();
        $_SESSION['publication'] = array();
        print_r(json_encode(array('result' => empty($_SESSION['publication']) ? true : false)));
    }


}