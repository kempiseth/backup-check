<?php

namespace PKEM\Model;

use PKEM\Controller\Route;

class Logic {

    protected $pageName;
    protected $data;

    function __construct($pageName) {
        $this->pageName = $pageName;
        $this->data = $this->generateData();
    }

    private function generateData() {
        return $this->{$this->pageName}();
    }

    public function getData() {
        return $this->data;
    }

    /**
     * @Page: start
     */
    public function start() {
        return [
            'title' => 'ចាប់ផ្ដើម',
            'page' => $this->pageName,
        ];
    }

    /**
     * @Page: not-found
     */
    public function not_found() {
        $_SESSION['message'] = "រកមិនឃើញទំព័រ ដែលអ្នកចង់បាន!";
        return [
            'title' => 'រកមិនឃើញ',
            'page' => $this->pageName,
        ];
    }

    /**
     * @Page: manage-system
     */
    public function manage_system() {
        // Insert a new user:
        if (isset($_POST['username'])) {
            $user = new User($_POST['username'], $_POST['password'], $_POST['roles']);
            $user->insertIntoDB();
        }

        // Users' list:
        $dbh = (new DB())->dbh;
        $sql = "SELECT * FROM " . User::TABLE_NAME;
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return [
            'title' => 'គ្រប់គ្រងប្រព័ន្ធ',
            'page' => $this->pageName,
            'users' => $users,
        ];
    }

    /**
     * @Page: login
     */
    public function login() {
        if (isset($_POST['username'])) {
            $user = new User($_POST['username'], $_POST['password']);
            if ($user->isValid()) {
                $_SESSION['userid'] = $user->getId();
                Route::routeTo(START_PATH);
            } else {
                $_SESSION['message'] = "ឈ្មោះអ្នកប្រើឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ";
            }
        }

        return [
            'title' => 'ចូលប្រព័ន្ធ',
            'page' => $this->pageName,
        ];
    }

    /**
     * @Page: human-resource
     */
    public function human_resource() {
        // Insert a staff:
        if (isset($_POST['name'])) {
            $staff = new Staff($_POST);
            $staff->insertIntoDB();
        }

        $is_active = 1;
        $LIMIT = 20;
        if (isset($_GET['terminated'])) {
            $is_active = 0;
            $LIMIT = 50;
        }

        // Staffs' list:
        $dbh = (new DB())->dbh;
        $sql = "SELECT s.id id, s.name name, s.sex sex, s.dob dob,
            w.position position, w.is_active is_active
            FROM _staff s JOIN _work w ON s.id=w.staff_id
            WHERE is_active=$is_active
            ORDER BY s.id DESC LIMIT $LIMIT";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $staffs = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return [
            'title' => 'ធនធានមនុស្ស',
            'page' => 'hr',
            'staffs' => $staffs,
        ];
    }

    /**
     * @Page: human-resource-day-off
     */
    public function human_resource_day_off() {
        if (isset($_POST['staff_id'])) {
            $dbh = (new DB())->dbh;
            $sql = "INSERT INTO _day_off SET 
                staff_id=:staff_id, from_date=:from_date, to_date=:to_date, description=:description";
            $stmt = $dbh->prepare($sql);
            $stmt->execute($_POST);
            Route::routeTo(HR_PATH.'/detail?staff_id='.$_POST['staff_id']);
        }

        if (isset($_GET['staff_id'])) {
            $dbh = (new DB())->dbh;
            $sql = "SELECT id, name FROM _staff s WHERE s.id=:staff_id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':staff_id', $_GET['staff_id']);
            $stmt->execute();
            $staff = $stmt->fetch(\PDO::FETCH_OBJ);

            return [
                'title' => 'សុំច្បាប់',
                'page' => 'hr-day-off',
                'staff' => $staff,
            ];
        }
    }

    private function human_resource_detail() {
        
        // Update a shift:
        if (isset($_POST['shift_id'])) {
            $data = $_POST;
            unset($data['staff_id']);
            $data['work_days'] = json_encode($data['work_days']);
            $data['work_times'] = json_encode($data['work_times']);
            if ($data['end_date']) {
                $end_date = 'end_date=:end_date,';
            } else {
                $end_date = '';
                unset($data['end_date']);
            }

            $dbh = (new DB())->dbh;
            $sql = "UPDATE _shift SET 
                position=:position, salary=:salary, start_date=:start_date, 
                $end_date work_days=:work_days, work_times=:work_times
                WHERE id=:shift_id";
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);
        }

        // Insert a new shift:
        else if (isset($_POST['position'])) {
            $data = $_POST;
            $data['work_days'] = json_encode($data['work_days']);
            $data['work_times'] = json_encode($data['work_times']);
            if ($data['end_date']) {
                $end_date = 'end_date=:end_date,';
            } else {
                $end_date = '';
                unset($data['end_date']);
            }

            $dbh = (new DB())->dbh;
            $sql = "INSERT INTO _shift SET 
                staff_id=:staff_id, position=:position, salary=:salary, 
                start_date=:start_date, $end_date work_days=:work_days, work_times=:work_times";
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);
        }

        // Staff's details:
        $dbh = (new DB())->dbh;
        $sql = "SELECT * FROM _staff s JOIN _work w ON s.id=w.staff_id
            WHERE s.id=:staff_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':staff_id', $_GET['staff_id']);
        $stmt->execute();
        $staff = $stmt->fetch(\PDO::FETCH_OBJ);

        // Staff's shifts:
        $sql = "SELECT * FROM _shift WHERE staff_id=:staff_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':staff_id', $_GET['staff_id']);
        $stmt->execute();
        $shifts = $stmt->fetchAll(\PDO::FETCH_OBJ);

        // Staff's day-off:
        $sql = "SELECT * FROM _day_off WHERE staff_id=:staff_id LIMIT 5";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':staff_id', $_GET['staff_id']);
        $stmt->execute();
        $day_offs = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $_return = [
            'page' => 'hr-detail',
            'details' => $staff,
            'shifts' => $shifts,
            'day_offs' => $day_offs,
        ];

        if ($staff) {
            $_return['title'] = $staff->name;
        } else {
            $_SESSION['message'] = "រកមិនឃើញបុគ្គលិក";
            $_return['title'] = "មិនឃើញ";
        }

        return $_return;
    }

    private function human_resource_edit() {
        $dbh = (new DB())->dbh;
        $sql = "SELECT * FROM _staff s JOIN _work w ON s.id=w.staff_id
            WHERE s.id=:staff_id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':staff_id', $_GET['staff_id']);
        $stmt->execute();
        $staff = $stmt->fetch(\PDO::FETCH_OBJ);

        $_return = [
            'page' => 'hr-edit',
            'staff' => $staff,
        ];

        if ($staff) {
            $_return['title'] = $staff->name;
        } else {
            $_SESSION['message'] = "រកមិនឃើញបុគ្គលិក";
            $_return['title'] = "មិនឃើញ";
        }

        return $_return;
    }

    /**
     * @Page: account
     */
    public function account() {
        if (isset($_POST['password'])) {
            if ($_POST['password'] == $_POST['confirm-password']) {
                //Update Password:
                $dbh = (new DB())->dbh;
                $sql = "UPDATE " . User::TABLE_NAME . " SET password=:password WHERE id=:id";
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(':password', User::hashPassword($_POST['password']));
                $stmt->bindValue(':id', $_SESSION['userid']);
                $stmt->execute();
                Route::routeTo(LOGOUT_PATH);
            } else {
                $_SESSION['message'] = "ពាក្យសម្ងាត់ដែលបានបញ្ចូលមិនត្រូវគ្នា";
            }
        }

        return [
            'title' => 'គណនី',
            'page' => $this->pageName,
        ];
    }

}
