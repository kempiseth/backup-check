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
        $sql = "SELECT * FROM ".User::TABLE_NAME;
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
        if ( isset($_POST['username']) ) {
            $user = new User($_POST['username'], $_POST['password']);
            if ( $user->isValid() ) {
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
        // Staffs' list:
        $dbh = (new DB())->dbh;
        $sql = "SELECT s.id id, s.name name, s.sex sex, s.dob dob, w.position position
            FROM _staff s JOIN _work w ON s.id=w.staff_id
            ORDER BY s.id DESC LIMIT 10";
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
     * @Page: account
     */
    public function account() {
        if ( isset($_POST['password']) ) {
            if ($_POST['password'] == $_POST['confirm-password']) {
                //Update Password:
                $dbh = (new DB())->dbh;
                $sql = "UPDATE ".User::TABLE_NAME." SET password=:password WHERE id=:id";
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
