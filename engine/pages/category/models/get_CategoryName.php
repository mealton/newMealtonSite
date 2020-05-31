<?php


class get_CategoryName extends main_Model
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
        $sql = "SELECT * FROM `new_project_publications_rubrics` WHERE `isActive` != 'deleted'";
        $this->data = $this->get_assoc_array(db::getInstance()->Select($sql));
    }

    private function get_assoc_array($data)
    {
        $result = array();
        foreach ($data as $row) {
            $result[$row['rubric_url_name']] = $row['rubric_name'];
        }
        return $result;
    }


}