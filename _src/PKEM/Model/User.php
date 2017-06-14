<?php

namespace PKEM\Model;

class User {

    const TABLE_NAME = "_user";
    const HASH_ALGO = "sha256";
    const ADMIN_USER = "admin";
    const ADMIN_PASS = "Ad3!N";

    public $id = false;
    public $username;
    public $password;
    public $roles = [];
    public $date;

    function __construct($username=null, $password=null, $roles=[]) {
        $this->username = $username;
        $this->password = $password;
        $this->roles = is_string($roles) ? json_decode($roles) : $roles;

        $this->initialize();
    }

    private function initialize() {
//        $this->checkTable();
//        $this->checkRecords();
    }

    public function getId() {
        if ( ! $this->id ) {
            $dbh = (new DB())->dbh;
            $sql = "SELECT id FROM ".self::TABLE_NAME." WHERE username=:username";
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':username' => $this->username));
            $row = $stmt->fetch(\PDO::FETCH_OBJ);
            $this->id = $row->id;
        }
        return $this->id;
    }

    private function checkTable() {
        $dbh = (new DB())->dbh;
        $sql = "CREATE TABLE IF NOT EXISTS ".self::TABLE_NAME." (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(64) NOT NULL UNIQUE,
            password VARCHAR(128) NOT NULL,
            roles VARCHAR(64) NOT NULL,
            `date` DATE)";
        $dbh->exec($sql);
    }

    public function insertIntoDB() {
        if ( ! $this->hasUser($this->username) ) {
            $dbh = (new DB())->dbh;
            $sql = "INSERT INTO ".self::TABLE_NAME." (username, password, roles, `date`)
                VALUES(:username, :password, :roles, CURDATE())";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':username', $this->username);
            $stmt->bindValue(':password', self::hashPassword($this->password));
            $stmt->bindValue(':roles', json_encode($this->roles));
            $stmt->execute();
        } else {
            $_SESSION['message'] = "អ្នកប្រើប្រាស់ <code>{$this->username}</code> មានរួចហើយ";
        }
    }

    private function checkRecords() {
        if ( ! $this->hasUser(self::ADMIN_USER) ) {
            $dbh = (new DB())->dbh;
            $sql = "INSERT INTO ".self::TABLE_NAME." (username, password, roles, `date`)
                VALUES(:username, :password, :roles, CURDATE())";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':username', self::ADMIN_USER);
            $stmt->bindValue(':password', self::hashPassword(self::ADMIN_PASS));
            $stmt->bindValue(':roles', json_encode(['select','insert','update','delete']));
            $stmt->execute();
        }
    }

    public function isValid() {
        return $this->isUsernameValid() && $this->isPasswordValid();
    }

    private function isUsernameValid() {
        return $this->hasUser($this->username);
    }

    private function isPasswordValid() {
        $dbh = (new DB())->dbh;
        $hash = self::hashPassword($this->password);
        $sql = "SELECT COUNT(*) FROM ".self::TABLE_NAME." WHERE username='{$this->username}' 
            AND password='{$hash}'";
        return ($result = $dbh->query($sql)) && ($result->fetchColumn() > 0);
    }

    static function hashPassword($password) {
        return hash(self::HASH_ALGO, $password);
    }

    private function hasUser($username) {
        $dbh = (new DB())->dbh;
        $sql = "SELECT COUNT(*) FROM " . self::TABLE_NAME . " WHERE username='$username'";
        return ($result = $dbh->query($sql)) && ($result->fetchColumn() > 0);
    }

    public function canInsert() {
        return in_array('insert', $this->roles);
    }

    public function canDelete() {
        return in_array('delete', $this->roles);
    }

    public function canUpdate() {
        return in_array('update', $this->roles);
    }

}
