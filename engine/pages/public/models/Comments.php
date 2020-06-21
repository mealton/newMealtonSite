<?php


class Comments extends main_Model
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
        $this->data = $this->arguments['action'] == 'get' ?
            $this->getComments($this->arguments['post_id']) :
            $this->setComment($this->arguments);
    }

    private function getComments($post_id)
    {
        if (!intval($post_id))
            return false;


        $result = array();

        $sql = 'SELECT `new_project_comments`.*,`new_project_comments`.`id` as `c_id`, 
                  `new_project_users`.`username` as `username`,`new_project_users`.`profile_image` as `profile_image`
                    FROM `new_project_comments` 
                      INNER JOIN `new_project_users` ON `new_project_users`.`id` = `new_project_comments`.`user_id`
                        WHERE `new_project_comments`.`post_id` = ' . $post_id . '
                            AND `new_project_comments`.`status` = 1
                                AND `new_project_comments`.`is_reply` = 0
                                    ORDER BY `new_project_comments`.`id` DESC';


        $comments = db::getInstance()->Select($sql);

        $comments_replied_ids = array();
        foreach ($comments as $key => $row){
            $sql = 'SELECT `src` FROM `new_project_comment_pictures` WHERE `comment_id` = ' . $row['id'];
            $comments[$key]['img'] = !empty(db::getInstance()->Select($sql)) ? db::getInstance()->Select($sql) : '';
            if($row['is_replied'])
                $comments_replied_ids[] = $row['id'];
        }

        $sql_replies = 'SELECT `new_project_comments`.*,`new_project_comments`.`id` as `c_id`, 
                          `new_project_users`.`username` as `username`,`new_project_users`.`profile_image` as `profile_image` 
                          FROM `new_project_comments` 
                            INNER JOIN `new_project_users` ON `new_project_users`.`id` = `new_project_comments`.`user_id`
                              WHERE `new_project_comments`.`post_id` = ' . $post_id .
                                ' AND `new_project_comments`.`status` = 1 
                                    AND `new_project_comments`.`is_reply` = 1 
                                        AND `new_project_comments`.`comment_id_reply` IN (' . implode(',', $comments_replied_ids) . ')';

        $replies_ids = array();

        if(!empty($comments_replied_ids)){
            $replies = db::getInstance()->Select($sql_replies);
            //return $sql_replies;
            foreach ($replies as $key => $row){
                $sql = 'SELECT `src` FROM `new_project_comment_pictures` WHERE `comment_id` = ' . $row['id'];
                $replies_ids[$row['comment_id_reply']][$key] = $row;
                $replies_ids[$row['comment_id_reply']][$key]['img'] = !empty(db::getInstance()->Select($sql)) ? db::getInstance()->Select($sql) : '';
            }
        }

        foreach ($comments as $key => $row){
            $comments[$key]['replies'] = $replies_ids[$row['id']] ? $replies_ids[$row['id']] : '';
        }



/*        foreach ($comments as $key => $comment){
            //Добавляем ответы на комментарий
            $sql = 'SELECT *
                      FROM `new_project_comments`
                        WHERE `post_id` = ' . $post_id . '
                            AND `comment_id` = ' . $comment['id'] . '
                                AND `status` = 1
                                    AND `isReply` = 1
                                        ORDER BY `id` DESC';
            //$comments[$key]['replies'] = db::getInstance()->Select($sql);
            $replies = db::getInstance()->Select($sql);
            if(!empty($replies)){
                foreach ($replies as $k => $reply){
                    $sql = 'SELECT `src` FROM `comment_pictures` WHERE `comment_id` = ' . $reply['id'];
                    $replies[$k]['images'] = db::getInstance()->Select($sql);
                }
            }
            $comments[$key]['replies'] = $replies;
            //Добавялем картинки
            $sql = 'SELECT `src` FROM `comment_pictures` WHERE `comment_id` = ' . $comment['id'];
            $comments[$key]['images'] = db::getInstance()->Select($sql);
        }*/
        return $comments;
    }

    private function setComment($comment)
    {

        $post_id = intval($comment['post_id']);
        $user_id = intval($comment['user_id']);
        $is_reply = intval($comment['is_reply']);
        $is_replied = intval($comment['is_replied']);
        $comment_id_reply = intval($comment['comment_id_reply']);
        $images = is_array($comment['images']) ? $comment['images'] : array();

        if (!$post_id || !$user_id)
            return false;


        $comment = addslashes(strval($comment['comment']));

        $sql = "INSERT INTO `new_project_comments` (`post_id`, `user_id`, `comment`, `status`, `is_reply`, `is_replied`, `comment_id_reply`) 
                  VALUES 
                    ($post_id, $user_id, \"" .  $comment . "\", 1, $is_reply, $is_replied, $comment_id_reply)";

        $id = db::getInstance()->QueryInsert($sql);

        if (!$id)
            return false;

        $query_src = '';
        foreach($images as $image){
            $query_src .= "($id, '$image'),";
        }
        $query_src = trim($query_src, ',');

        $sql = 'INSERT INTO `new_project_comment_pictures`
                  (`comment_id`, `src`)
                    VALUES ' . $query_src;


        if($query_src)
            db::getInstance()->QueryInsert($sql);

        if($is_reply && $comment_id_reply){
            db::getInstance()->Query('UPDATE `new_project_comments` SET `is_replied` = 1 WHERE `id` = ' . $comment_id_reply);
        }

        return $this->getComments($post_id);
    }


}