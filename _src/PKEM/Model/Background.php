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
            WHERE CONCAT_WS('|',LPAD(s.id,4,'0'),name,sex,dob,phone,address,education,skill,language,position,department)
            LIKE :text LIMIT 30";
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
        $sql = "UPDATE ".Staff::WORK_TABLE." SET is_active=0, leave_date=CURDATE(), leave_reason=:leave_reason WHERE staff_id=:staff_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':leave_reason', $_POST['reason']);
        $stmt->bindValue(':staff_id', $_POST['staff_id']);
        $_return = $stmt->execute();
        echo $_return ? 'OK' : 'FAILED';
    }

    private function editStaff() {
        $data = $_POST;
        $old_is_active = $data['old_is_active'];
        
        unset($data['_ajax']);
        unset($data['old_is_active']);

        $leave_date = $data['is_active'] ? 'NULL' : 'CURDATE()';
        if ($data['is_active']) {
            $data['leave_reason'] = '';
        }

        $set_leave_date = 
            ($old_is_active == $data['is_active']) ? '' : "w.leave_date=$leave_date,";

        $dbh = (new DB())->dbh;
        $sql = "UPDATE _staff s JOIN _work w ON (s.id = w.staff_id)
          SET w.is_active=:is_active, s.name=:name, s.sex=:sex, s.photo=:photo, s.dob=:dob, s.phone=:phone,
            s.address=:address, s.education=:education, s.skill=:skill, s.language=:language, w.position=:position,
            w.department=:department, w.enroll_date=:enroll_date, $set_leave_date w.leave_reason=:leave_reason, w.salary=:salary
          WHERE s.id=:staff_id";
        $stmt = $dbh->prepare($sql);
        $_return = $stmt->execute($data);

        $_SESSION['message'] = $_return ? '' : 'មានបញ្ហាពេលកំពុងកែប្រែបុគ្គលិក';
        Route::routeTo(HR_PATH.'/detail?staff_id='.$_POST['staff_id']);
    }

}
