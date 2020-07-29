<?php


class public_Controller extends main_Controller
{

    public function get_page($config, $arguments = array())
    {
        session_start();
        $refer = array_values(array_diff(explode('/', str_replace('http://' . $_SERVER['HTTP_HOST'], '', urldecode($_SERVER['HTTP_REFERER']))), array('')));

        if ($refer[0] != 'public') {
            $_SESSION['condition'] = array(
                'condition' => $refer[0],
                'value' => $refer[1],
            );
        }

        $alias = $arguments[1];
        $data = $alias == 'preview' ?
            $this->getSessionPublic() :
            $this->executeModel($config, 'public', 'get_Content', array('id' => current(explode("::", $alias))));


        $short_title = $data[0]['short_title'];
        $long_title = $data[0]['long_title'] ? $data[0]['long_title'] : $short_title;
        $description = $data[0]['description'] ? $data[0]['description'] : $long_title;

        if (!strpos($_SERVER['HTTP_REFERER'], '/profile/'))
            $_SESSION['referer'] = $_SERVER['HTTP_REFERER'];

        $prev_next = $this->executeModel($config, 'public', 'get_Prev_Next_Public', array('id' => current(explode("::", $alias)), 'created_on' => $data[0]['created_on']));

        $prev = $this->render('public', array(
            'view' => 'prev',
            'data' => $prev_next['prev']
        ));
        $next = $this->render('public', array(
            'view' => 'next',
            'data' => $prev_next['next']
        ));

        $prev_next_html = $this->render('public', array(
            'view' => 'prev_next',
            'data' => array(
                array(
                    'prev' => $prev,
                    'next' => $next
                )
            )
        ));

        $comments = $this->executeModel($config, 'public', 'Comments', array('post_id' => $data[0]['publication_id'], 'action' => 'get'));

        $hashtags =
            '<span class="hashtag-item">
                <a href="/category#hashtags">
                    <span>все теги</span>
                </a>
            </span>' . (!empty($data[0]['hashtags-counter']) ?
            $this->render('public', array(
                'view' => 'hashtag',
                'data' => $data[0]['hashtags-counter']
            )) : '');

        //main_Model::pre($hashtags);


        $data[0]['comments'] = $this->commentsHTML($comments);

        $this->executeView('public', array(
            array('view' => 'onePublicTitle',
                'data' => array(
                    'title' => $short_title,
                    'long_title' => $long_title,
                    'description' => $description,
                    'username' => $data[0]['username'],
                    'views' => $data[0]['views'],
                    'count_img' => $data[0]['count_img'],
                    'count_video' => $data[0]['count_video'],
                    'prev_next_html' => $prev_next_html
                ),
                'container' => 'onePublicTitle'),
            // array('view' => 'get_publication_item', 'data' => $data, 'container' => 'publication'),
            array('view' => 'switch_tag', 'data' => $data, 'container' => 'publication'),
            array('view' => 'footer', 'data' => array(
                //'dataFooter' => $dataFooter,
                'username' => $data[0]['username'],
                'profile_image' => $data[0]['profile_image'],
                'publication_id' => $data[0]['publication_id'],
                'alias' => $data[0]['alias'],
                'likes' => $data[0]['likes'],
                'dislikes' => $data[0]['dislikes'],
                'import' => $data[0]['imported'],
                'hashtags' => $hashtags,
                'referer' => $_SESSION['referer'],
                'user_id' => $_SESSION['userId'],
                'prev_next_html' => $prev_next_html,
                'comments' => $data[0]['comments']
            ), 'container' => 'footer'),
            //array('view' => 'comment', 'data' => $dataComments, 'container' => 'comments')
        ));

        /*if (!$arguments['url_name']) {
            $this->executeView('public', array(
                array('view' => 'get_title', 'data' => array('title' => 'Публикации'), 'container' => 'title'),
                array('view' => 'get_post', 'data' => $data, 'container' => 'posts'),
                array('view' => 'get_footer')
            ));
        } else {
            $dataTitle = array(
                'title' => $data[0]['title'],
                'author' => $data[0]['author'],
                'username' => $data[0]['username'],
                'views' => $data[0]['views']
            );
            $dataFooter = array(
                'import' => $data[0]['import'],
                'likes' => $data[0]['likes'],
                'post_id' => $data[0]['post_id']
            );

            $dataComments = $this->executeModel($config, 'public', 'Comments', array('post_id' => $data[0]['id'], 'action' => 'get'));

            if(is_array($dataComments)){
                foreach ($dataComments as $key => $dataComment){
                    //Ответы на комментарий
                    $dataReplies = $dataComment['replies'];
                    $dataImages = $dataComment['images'];
                    if(!empty($dataReplies)){

                        //КАртинки к ответам на комментарий
                        foreach ($dataReplies as $k => $reply){
                            $dataImagesReplies = $reply['images'];
                            if(!empty($dataImagesReplies)){
                                $imagesReplies = $this->render('public', array(
                                    'view' => 'comment_image',
                                    'data' => $dataImagesReplies
                                ));
                                $dataReplies[$k]['images'] = $imagesReplies;
                            }else{
                                $dataReplies[$k]['images'] = '';
                            }
                        }

                        $replies = $this->render('public', array(
                            'view' => 'comment',
                            'data' => $dataReplies
                        ));
                        $dataComments[$key]['replies'] = $replies;
                    }else{
                        $dataComments[$key]['replies'] = '';
                    }
                    //Картинки в комментарии
                    if(!empty($dataImages)){
                        $images = $this->render('public', array(
                            'view' => 'comment_image',
                            'data' => $dataImages
                        ));
                        $dataComments[$key]['images'] = $images;
                    }else{
                        $dataComments[$key]['images'] = '';
                    }

                }
            }

        }*/

    }

    private function commentsHTML($comments)
    {
        if (!is_array($comments) || empty($comments))
            return '';

        foreach ($comments as $key => $comment) {
            if (!empty($comment['img'])) {
                $comments[$key]['img'] = $this->render('public', array(
                    'view' => 'comment_image',
                    'data' => $comment['img']
                ));
            } else {
                $comments[$key]['img'] = '';
            }
            if (!empty($comment['replies'])) {

                foreach ($comment['replies'] as $i => $reply) {
                    if (!empty($reply['img'])) {
                        $comments[$key]['replies'][$i]['img'] = $this->render('public', array(
                            'view' => 'comment_image',
                            'data' => $reply['img']
                        ));
                    } else {
                        $comments[$key]['replies'][$i]['img'] = '';
                    }
                }

                $comments[$key]['replies'] = '<div class="replies">' . $this->render('public', array(
                        'view' => 'comment',
                        'data' => $comments[$key]['replies']
                    )) . '</div>';
            }
        }

        return $this->render('public', array(
            'view' => 'comment',
            'data' => $comments
        ));

    }

    protected function getSessionPublic()
    {
        session_start();
        $publication = array();
        foreach ($_SESSION['publication']['fields'] as $field) {
            $publication[] = array(
                'id' => $field['id'],
                'category' => $_SESSION['publication']['category'],
                'alias' => $_SESSION['publication']['alias'],
                'short_title' => $_SESSION['publication']['short_title'],
                'long_title' => $_SESSION['publication']['long_title'],
                'description' => $_SESSION['publication']['description'],
                'views' => 0,
                'likes' => 0,
                'dislikes' => 0,
                'tag_category' => $field['field'],
                'content' => $field['value'],
                'username' => $_SESSION['login'],
                'rubric_name' => $field['category']
            );
        }

        return $publication;


        //print_r(json_encode(array('publication' => $publication)));
    }

    protected function like($config, $data)
    {

        if ($data['to_do'] == 'like' && $_COOKIE['like-' . $data['id']] ||
            $data['to_do'] == 'dislike' && $_COOKIE['dislike-' . $data['id']]
        ) {
            return false;
        }

        if ($data['to_do'] == 'like' && $_COOKIE['dislike-' . $data['id']]) {
            setcookie('dislike-' . $data['id'], true, time() - 30 * 24 * 3600, "/");
            $data['act'] = 'default_dislike';
        } elseif ($data['to_do'] == 'like' && !$_COOKIE['dislike-' . $data['id']]) {
            setcookie('like-' . $data['id'], true, time() + 30 * 24 * 3600, "/");
            $data['act'] = 'set_like';
        } elseif ($data['to_do'] == 'dislike' && $_COOKIE['like-' . $data['id']]) {
            setcookie('like-' . $data['id'], true, time() - 30 * 24 * 3600, "/");
            $data['act'] = 'default_like';
        } elseif ($data['to_do'] == 'dislike' && !$_COOKIE['like-' . $data['id']]) {
            setcookie('dislike-' . $data['id'], true, time() + 30 * 24 * 3600, "/");
            $data['act'] = 'set_dislike';
        }

        $data = $this->executeModel($config, 'public', 'Likes', $data);
        $likesHTML = $this->render('public', array(
            'view' => 'likes',
            'data' => $data
        ));
        print_r(json_encode(array('result' => $data, 'html' => $likesHTML)));
        return true;
    }

    protected function comment($config, $data)
    {

        $arguments = $data;
        $data = $this->executeModel($config, 'public', 'Comments', $data['comment']);

        /*if(is_array($data)){
            foreach ($data as $key => $comment){
                if(!empty($comment['img'])){
                    $data[$key]['img'] = $this->render('public', array(
                        'view' => 'comment_image',
                        'data' => $comment['img']
                    ));
                }else{
                    $comments[$key]['img'] = '';
                }
                if(!empty($comment['replies'])){
                    $data[$key]['replies'] = '<div class="replies">' . $this->render('public', array(
                            'view' => 'comment',
                            'data' => $comment['replies']
                        )) . '</div>';
                }
            }
        }*/

        /*$html = $this->render('public', array(
            'view' => 'comment',
            'data' => $data
        ));*/

        $html = $this->commentsHTML($data);
        $result = !empty($data[0]) ? 'true' : 'false';

        print_r(json_encode(array('result' => $result, 'comment' => $html, 'data' => $data, 'arguments' => $arguments)));
    }

    protected function commentLike($config, $data)
    {
        $likeDislike = $data['likeDislike'];
        $id = $data['id'];
        if ($_COOKIE['com-' . $likeDislike . '-' . $id]) {
            $sql = "UPDATE `new_project_comments`
                    SET `$likeDislike` = `$likeDislike` - 1
                        WHERE `id` = " . $id;
            setcookie('com-' . $likeDislike . '-' . $id, true, time() - 30 * 24 * 3600, "/");
        } else {
            $sql = "UPDATE `new_project_comments`
                    SET `$likeDislike` = `$likeDislike` + 1
                        WHERE `id` = " . $id;
            setcookie('com-' . $likeDislike . '-' . $id, true, time() + 30 * 24 * 3600, "/");
        }

        $data['sql'] = $sql;
        $data = $this->executeModel($config, 'public', 'CommentLike', $data);
        $result = !empty($data[0]) ? 'true' : 'false';
        print_r(json_encode(array('result' => $result, 'data' => $data)));
        return true;
    }

    protected function commentReply($config, $data)
    {
        $data = $this->executeModel($config, 'public', 'Comments', $data);
        $dataImages = $data[0]['images'];
        if ($dataImages) {
            $images = $this->render('public', array(
                'view' => 'comment_image',
                'data' => $dataImages
            ));
            $data[0]['images'] = $images;
        } else {
            $data[0]['images'] = '';
        }
        $html = $this->render('public', array(
            'view' => 'comment',
            'data' => $data
        ));
        $result = !empty($data[0]) ? 'true' : 'false';
        print_r(json_encode(array('result' => $result, 'comment' => $html, 'data' => $data)));
    }


}