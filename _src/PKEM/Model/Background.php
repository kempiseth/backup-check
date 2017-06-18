<?php

namespace PKEM\Model;

use PKEM\Controller\Route;

class Background {

    protected $action;

    function __construct($action) {
        $this->action = $action;
        $this->{$action}();
    }

    private function logout() {
        session_unset();
        Route::routeTo(LOGIN_PATH);
    }

    private function deleteUser() {
        $dbh = (new DB())->dbh;
        $sql = "DELETE FROM ".User::TABLE_NAME." WHERE id=:id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $_POST['userid']);
        $_return = $stmt->execute();
        echo $_return ? 'OK' : 'FAILED';
    }

    private function searchStaff() {
        $dbh = (new DB())->dbh;
        $sql = "SELECT s.id id, s.name name, s.sex sex, s.dob dob, w.position position, w.is_active is_active
            FROM _staff s JOIN _work w ON s.id=w.staff_id
            WHERE CONCAT_WS(' ',name,sex,dob,phone,address,education,skill,language,position,department) LIKE :text
            LIMIT 30";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':text', '%'.$_POST['text'].'%');
        $stmt->execute();
        $staffs = $stmt->fetchAll(\PDO::FETCH_OBJ);
        echo json_encode($staffs);
    }

    private function removeStaff() {
        $dbh = (new DB())->dbh;
        $sql = "DELETE FROM ".Staff::TABLE_NAME." WHERE id=:id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':id', $_POST['staff_id']);
        $_return = $stmt->execute();
        echo $_return ? 'OK' : 'FAILED';
    }

    private function disableStaff() {
        $dbh = (new DB())->dbh;
        $sql = "UPDATE ".Staff::WORK_TABLE." SET is_active=0 WHERE staff_id=:staff_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':staff_id', $_POST['staff_id']);
        $_return = $stmt->execute();
        echo $_return ? 'OK' : 'FAILED';
    }

}
