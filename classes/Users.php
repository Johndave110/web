<?php
require_once "Database.php";

class Users extends Database {
    public $user_id = "";
    public $username = "";
    public $password = "";
    public $role = "";
    public $profile_id = "";
    public $date_created = "";

    public function addUser() {
    $check = $this->connect()->prepare("SELECT * FROM users WHERE username = :username");
    $check->bindParam(':username', $this->username);
    $check->execute();

    if ($check->rowCount() > 0) {
        return 'duplicate';
    }

    $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, role, profile_id, date_created)
            VALUES (:username, :password, :role, :profile_id, NOW())";

    $query = $this->connect()->prepare($sql);
    $query->bindParam(':username', $this->username);
    $query->bindParam(':password', $hashedPassword);
    $query->bindParam(':role', $this->role);
    $query->bindParam(':profile_id', $this->profile_id);

    if ($query->execute()) {
        return true;
    }
    return false;
    }


    public function login($username, $password){
    $sql = "SELECT * FROM users WHERE username = :username";
    $query = $this->connect()->prepare($sql);
    $query->bindParam(':username', $username);
    $query->execute();

    $user = $query->fetch();

    if ($user) {
        $storedPassword = $user['password'];

        //hash checker
        if (password_get_info($storedPassword)['algo']) {
            if (password_verify($password, $storedPassword)) {
                return $user;
            }
        } else {
            // created admin account password convert to random shit
            if ($password === $storedPassword) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $update = $this->connect()->prepare("UPDATE users SET password = :password WHERE username = :username");
                $update->bindParam(':password', $newHash);
                $update->bindParam(':username', $username);
                $update->execute();

                return $user;
            }
        }
    }
    return false;
    }

}
