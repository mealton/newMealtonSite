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
            $this->setComment($this->arguments['comment']);
    }

    private function getComments($post_id)
    {
        if (!intval($post_id))
            return false;
        $sql = 'SELECT *
                    FROM `comments`
                        WHERE `post_id` = ' . $post_id . '
                            AND `status` != "deleted"
                                AND `isReply` = 0
                                    ORDER BY `id` DESC';


        $comments = db::getInstance()->Select($sql);
        foreach ($comments as $key => $comment){
            //Добавляем ответы на комментарий
            $sql = 'SELECT *
                      FROM `comments`
                        WHERE `post_id` = ' . $post_id . '
                            AND `comment_id` = ' . $comment['id'] . '
                                AND `status` != "deleted"
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
        }
        return $comments;
    }

    private function setComment($comment)
    {

        $post_id = intval($comment['post_id']);
        if (!$post_id)
            return false;
        $comment_id = intval($comment['comment_id']);
        $username = addslashes(strval($comment['username']));
        $commentText = addslashes(strval($comment['comment']));
        //$pic = addslashes(strval($comment['pic']));
        $images = $comment['images'];
        $sql = !$comment['reply'] ?
            "INSERT INTO `comments`
                    (`post_id`, `username`, `com_text`, `isReply`) 
                        VALUES ($post_id, \"$username\", \"$commentText\", 0)" :
            "INSERT INTO `comments`
                (`post_id`, `comment_id`, `username`, `com_text`, `isReply`) 
                        VALUES ($post_id, $comment_id, \"$username\", \"$commentText\", 1)";
        $id = db::getInstance()->QueryInsert($sql);

        if (!$id)
            return false;

        $query_src = '';
        foreach($images as $image){
            $query_src .= "($id, '$image'),";
        }
        $query_src = trim($query_src, ',');

        $sql = 'INSERT INTO `comment_pictures`
                  (`comment_id`, `src`)
                    VALUES ' . $query_src;


        db::getInstance()->QueryInsert($sql);

        $sql = 'SELECT *
                    FROM `comments`
                        WHERE `id` = ' . $id . '
                            LIMIT 1';
        $comment = db::getInstance()->Select($sql);
        $sql = 'SELECT `src` FROM `comment_pictures`
                  WHERE `comment_id` = ' . $id;
        $comment[0]['images'] = db::getInstance()->Select($sql);
        return $comment;
    }


}