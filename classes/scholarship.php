<?php
require_once "Database.php";

class Scholarship extends Database {
    public $scholarship_id;
    public $title;
    public $description;
    public $requirements;
    public $deadline;
    public $total_slots;
    public $available_slots;
    public $min_gpa;
    public $created_at;

    public function isScholarshipExist($title){
        $sql = "SELECT * FROM scholarships WHERE title = :title";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':title', $title);
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function addScholarship(){
        $sql = "INSERT INTO scholarships 
                (title, description, requirements, deadline, total_slots, available_slots, min_gpa, created_at)
                VALUES (:title, :description, :requirements, :deadline, :total_slots, :available_slots, :min_gpa, NOW())";
        $query = $this->connect()->prepare($sql);

        $query->bindParam(':title', $this->title);
        $query->bindParam(':description', $this->description);
        $query->bindParam(':requirements', $this->requirements);
        $query->bindParam(':deadline', $this->deadline);
        $query->bindParam(':total_slots', $this->total_slots);
        $query->bindParam(':available_slots', $this->available_slots);
        $query->bindParam(':min_gpa', $this->min_gpa);

        return $query->execute();
    }

    public function getScholarships($limit = 10, $offset = 0){
        $sql = "SELECT * FROM scholarships ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $query = $this->connect()->prepare($sql);
        $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $query->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countScholarships(){
        $sql = "SELECT COUNT(*) as total FROM scholarships";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetch();
        return $result['total'] ?? 0;
    }

    public function getScholarshipById($id){
        $sql = "SELECT * FROM scholarships WHERE scholarship_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch();
    }

    public function updateScholarship($id){
        $sql = "UPDATE scholarships 
                SET title = :title, description = :description, requirements = :requirements, 
                    deadline = :deadline, total_slots = :total_slots, available_slots = :available_slots, 
                    min_gpa = :min_gpa
                WHERE scholarship_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':title', $this->title);
        $query->bindParam(':description', $this->description);
        $query->bindParam(':requirements', $this->requirements);
        $query->bindParam(':deadline', $this->deadline);
        $query->bindParam(':total_slots', $this->total_slots);
        $query->bindParam(':available_slots', $this->available_slots);
        $query->bindParam(':min_gpa', $this->min_gpa);
        $query->bindParam(':id', $id);
        return $query->execute();
    }

    public function deleteScholarship($id){
        $sql = "DELETE FROM scholarships WHERE scholarship_id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':id', $id);

        return $query->execute();
    }

    public function getRecentScholarships($limit = 3){
    $sql = "SELECT * FROM scholarships ORDER BY created_at DESC LIMIT :limit";
    $query = $this->connect()->prepare($sql);
    $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $query->execute();

    return $query->fetchAll();
    }
}