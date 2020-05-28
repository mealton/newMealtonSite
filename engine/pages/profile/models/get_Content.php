<?php


class get_Content extends main_Model
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
        $this->data = $this->arguments['publicId'] ?
            array(
                'publication' => $this->getPublic($this->arguments['publicId']),
                'categories' => $this->getCategories()
            )

            : array(
            'userdata' => $this->getUserdata(),
            'getUserPublications' => $this->getUserPublications(),
            'categories' => $this->getCategories()
        );
    }

    private function getUserdata()
    {
        $sql = "SELECT * FROM `new_project_users`
                    WHERE `id` = " . $this->arguments['userId'] .
                        " LIMIT 1";
        return db::getInstance()->Select($sql);
    }

    private function getUserPublications()
    {
        $sql = "SELECT *,
                  (SELECT `content` FROM `new_project_publications_content`
                    WHERE `publication_id` = `publics`.`id` AND `tag_category` = 'image'
                      ORDER BY RAND() LIMIT 1) as `random_img`
                   FROM `new_project_publications` as `publics`
                      WHERE `user_id` = " . $this->arguments['userId'] .
                        " ORDER BY `created_on` DESC 
                                LIMIT 50";
        return db::getInstance()->Select($sql);
    }

    private function getPublic($id)
    {
        $sql = 'SELECT `posts`.*, `posts`.`id` as `post_id`, `content`.*, `users`.`username`, `category`.`rubric_name`
                    FROM `new_project_publications` as `posts`
                      RIGHT JOIN `new_project_publications_content` as `content` ON `posts`.`id` = `content`.`publication_id`
                        RIGHT JOIN `new_project_users` as `users` ON `posts`.`user_id` = `users`.`id`
                            RIGHT JOIN `new_project_publications_rubrics` as `category` ON `posts`.`category` = `category`.`id`
                    WHERE `posts`.`id` = ' . $id;
        return db::getInstance()->Select($sql);
    }

    private function getCategories()
    {
        $sql = "SELECT * FROM `new_project_publications_rubrics` WHERE `isActive` = ''";
        return db::getInstance()->Select($sql);
    }


}