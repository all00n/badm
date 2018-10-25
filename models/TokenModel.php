<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 23.10.2018
 * Time: 13:48
 */
namespace core;
class TokenModel
{
    private $db;


    //создаём екземпляр класса из ядра для работы с бд
    public function __construct()
    {
        $dbh = new DB;
        $this->db = $dbh->db();
    }
    //метод возвращает id пользователя и токен
    public function getPermission($token) {
        //достаём информацию о юзере по переданому токену
        //токен у нас уникальный по этому не боимся каких то старых записей
        $sql = 'SELECT user_id FROM `token` t 
                JOIN `users` u on u.id=t.user_id 
                WHERE token = :token  and expire >= NOW() LIMIT 1';

        $stmt = $this->db->prepare($sql);

        $stmt->execute(array(':token' => $token));

        $row = $stmt->fetch();
        return $row;
    }
}