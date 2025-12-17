<?php
require_once "Database.php";

class Profile extends Database {
    public $profile_id;
    public $firstName;
    public $lastName;
    public $middleName;
    public $birthdate;
    public $address;
    public $contactNumber;
    public $gpa;
    public $familyIncome;
    public $school;
    public $course;
    public $yearLevel;

    public function addProfile() {
        $conn = $this->connect();

        $sql = "INSERT INTO profile 
                (firstName, lastName, middleName, birthdate, address, contactNumber, gpa, familyIncome, school, course, yearLevel)
                VALUES 
                (:firstName, :lastName, :middleName, :birthdate, :address, :contactNumber, :gpa, :familyIncome, :school, :course, :yearLevel)";

        $query = $conn->prepare($sql);

        // ✅ bind parameters
        $query->bindParam(":firstName", $this->firstName);
        $query->bindParam(":lastName", $this->lastName);
        $query->bindParam(":middleName", $this->middleName);
        $query->bindParam(":birthdate", $this->birthdate);
        $query->bindParam(":address", $this->address);
        $query->bindParam(":contactNumber", $this->contactNumber);
        $query->bindParam(":gpa", $this->gpa);
        $query->bindParam(":familyIncome", $this->familyIncome);
        $query->bindParam(":school", $this->school);
        $query->bindParam(":course", $this->course);
        $query->bindParam(":yearLevel", $this->yearLevel);

        // ✅ execute and return insert ID using the same connection
        if ($query->execute()) {
            return $conn->lastInsertId();
        } else {
            return false;
        }
    }

    public function viewProfile($user_id){
    $sql = "SELECT p.* FROM profile p INNER JOIN users u ON p.profile_id = u.profile_id WHERE u.user_id = :user_id";
    $query = $this->connect()->prepare($sql);
    $query->bindParam(":user_id", $user_id);

    if ($query->execute()) {
        return $query->fetch();
    } else {
        return null;
    }
    } 

    public function countProfiles() {
    $sql = "SELECT COUNT(*) as total FROM profile"; // make sure table name is correct
    $query = $this->connect()->prepare($sql);

    if ($query->execute()) {
        $row = $query->fetch();
        return $row['total'] ?? 0;
    } else {
        return 0;
    }
    }
}