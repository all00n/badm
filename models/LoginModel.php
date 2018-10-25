<?php
/**
 * Created by PhpStorm.
 * User: ALLOON
 * Date: 22.10.2018
 * Time: 21:56
 */

use core\DB;

class LoginModel
{
    private $db;

    public function __construct()
    {
        $dbh = new DB;
        $this->db = $dbh->db();
    }

    public function getPassword($userEmail){
        $sth = $this->db->prepare('select id, password from users where email=:email limit 1');

        $params = [
            ':email' => $userEmail
        ];

        $sth->execute($params);
        $res = $sth->fetch(PDO::FETCH_ASSOC);

        return $res;
    }

    public function remember($user_id, $expire = null)
    {
        $sql = 'INSERT INTO token (token, user_id, expire) VALUES (:token, :user_id, now() + INTERVAL :expire SECOND)';

        $stmt = $this->db->prepare($sql);
//делаем безконечный цикл для создания токена поле токен unique по этому если execute внесет запись (уникальную) и выйдет из цикла
        while (true) {
            try {
                if( $stmt->execute(array(
                    ':token' => $token = $this->generateToken(),
                    ':user_id' => $user_id,
                    ':expire' => $expire
                )) ){
                    break;
                }

            } catch (PDOException $e) {}
        }

        return $token;
    }

    private function generateToken()
    {
        return md5(uniqid('', true));
    }
}