<?php

namespace PKEM\Model;

class Staff {

    const TABLE_NAME = "_staff";
    const WORK_TABLE = "_work";
    const ID_PAD_LENGTH = 4;

    public $id;
    public $name;
    public $sex;
    public $dob;
    public $phone;
    public $address;
    public $education;
    public $skill;
    public $language;

    public $position;
    public $department;
    public $enroll_date;
    public $salary;
    public $is_active = 1;

    function __construct($data) {
        $this->name = $data['name'];
        $this->sex = $data['sex'];
        $this->dob = $data['dob'];
        $this->phone = $data['phone'];
        $this->address = $data['address'];
        $this->education = $data['education'];
        $this->skill = $data['skill'];
        $this->language = $data['language'];

        $this->position = $data['position'];
        $this->department = $data['department'];
        $this->enroll_date = $data['enroll_date'];
        $this->salary = $data['salary'];
    }

    public function insertIntoDB() {
        $dbh = (new DB())->dbh;
        $sql = "INSERT INTO ".self::TABLE_NAME." SET
            name=:name, sex=:sex, dob=:dob, phone=:phone, address=:address, education=:education, skill=:skill, language=:language";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':name', $this->name);
        $stmt->bindValue(':sex', $this->sex);
        $stmt->bindValue(':dob', $this->dob);
        $stmt->bindValue(':phone', $this->phone);
        $stmt->bindValue(':address', $this->address);
        $stmt->bindValue(':education', $this->education);
        $stmt->bindValue(':skill', $this->skill);
        $stmt->bindValue(':language', $this->language);
        $stmt->execute();

        $sql = "INSERT INTO ".self::WORK_TABLE." SET
            staff_id=LAST_INSERT_ID(), position=:position, department=:department, enroll_date=:enroll_date, salary=:salary";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':position', $this->position);
        $stmt->bindValue(':department', $this->department);
        $stmt->bindValue(':enroll_date', $this->enroll_date);
        $stmt->bindValue(':salary', $this->salary);
        $stmt->execute();
    }

    static function formatId($id) {
        return str_pad($id, self::ID_PAD_LENGTH, 0, STR_PAD_LEFT);
    }

}

