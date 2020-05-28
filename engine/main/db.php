<?php

class db
{
    private static $_instance = null;
    private $db; // Ресурс работы с БД

    /*
     * Получаем объект для работы с БД
     */

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new db();
        }
        return self::$_instance;
    }

    /*
     * Запрещаем копировать объект
     */

    private function __construct()
    {
    }

    private function __sleep()
    {
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }


    /*
     * Выполняем соединение с базой данных
     */

    public function Connect($user, $password, $base, $host = 'localhost', $port = 3306)
    {
        // Формируем строку соединения с сервером
        $connectString = 'mysql:host=' . $host . ';port= ' . $port . ';dbname=' . $base . ';charset=UTF8;';
        //$this->db->query("SET NAMES 'utf8'");
        $this->db = new PDO($connectString, $user, $password,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // возвращать ассоциативные массивы
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // возвращать Exception в случае ошибки
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]
        );
    }

    /*
     * Выполнить запрос к БД
     */

    public function Query($query, $params = array())
    {
        $res = $this->db->prepare($query);
        $res->execute($params);
        return $res;
    }

    public function QueryInsert($query)
    {
        $this->db->exec($query);
        return $this->db->lastInsertId();
    }

    /*
     * Выполнить запрос с выборкой данных
     */

    public function Select($query, $params = array())
    {
        $result = $this->Query($query, $params);
        if ($result) {
            return $result->fetchAll();
        }
    }
}
?>