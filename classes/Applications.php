<?php
require_once "Database.php";

class Application extends Database {
    public $application_id;
    public $student_id;
    public $scholarship_id;
    public $upload_file;
    public $status;
    public $applied_at;

    // Add new application
    public function addApplication() {
        $sql = "INSERT INTO applications 
                (student_id, scholarship_id, upload_file, status, applied_at) 
                VALUES (:student_id, :scholarship_id, :upload_file, :status, :applied_at)";
        $query = $this->connect()->prepare($sql);

        $query->bindParam(":student_id", $this->student_id);
        $query->bindParam(":scholarship_id", $this->scholarship_id);
        $query->bindParam(":upload_file", $this->upload_file);
        $query->bindParam(":status", $this->status);
        $query->bindParam(":applied_at", $this->applied_at);

        return $query->execute();
    }

    // Check if a student has already applied for a scholarship
    public function hasApplied($student_id, $scholarship_id) {
        $sql = "SELECT * FROM applications 
                WHERE student_id = :student_id AND scholarship_id = :scholarship_id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":student_id", $student_id);
        $query->bindParam(":scholarship_id", $scholarship_id);

        if ($query->execute()) {
            return $query->rowCount() > 0;
        }
        return false;
    }

    // Get all applications of a student
    public function getApplicationsByStudent($student_id) {
        $sql = "SELECT a.*, s.title AS scholarship_title
                FROM applications a
                INNER JOIN scholarships s ON a.scholarship_id = s.scholarship_id
                WHERE a.student_id = :student_id
                ORDER BY a.applied_at DESC";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":student_id", $student_id);

        if ($query->execute()) {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    // Fetch all applications with student name and scholarship title (Admin view)
    public function getAllApplicationsWithDetails(){
        $sql = "SELECT a.*, p.firstName, p.lastName, s.title AS scholarship_title
            FROM applications a
            INNER JOIN users u ON a.student_id = u.user_id
            INNER JOIN profile p ON u.profile_id = p.profile_id
            INNER JOIN scholarships s ON a.scholarship_id = s.scholarship_id
            ORDER BY a.applied_at DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->normalizeUploadPaths($rows);
    }

    // Update application status (Approve/Reject)
    public function updateStatus($application_id, $status) {
        $sql = "UPDATE applications SET status = :status WHERE application_id = :application_id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':status', $status);
        $query->bindParam(':application_id', $application_id);

        return $query->execute();
    }

    // Count all applications
    public function countApplications() {
        $sql = "SELECT COUNT(*) as total FROM applications";
        $query = $this->connect()->prepare($sql);

        if ($query->execute()) {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        }
        return 0;
    }

    // Count applications by status
    public function countApplicationsByStatus($status) {
        $sql = "SELECT COUNT(*) as total FROM applications WHERE status = :status";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':status', $status);

        if ($query->execute()) {
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        }
        return 0;
    }

    // Get recent applications
    public function getRecentApplications($limit = 10) {
        $sql = "SELECT a.*, p.firstName, p.lastName, s.title AS scholarship_title
                FROM applications a
                JOIN profile p ON a.student_id = p.profile_id
                JOIN scholarships s ON a.scholarship_id = s.scholarship_id
                ORDER BY a.applied_at DESC
                LIMIT :limit";
        $query = $this->connect()->prepare($sql);
        $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        if ($query->execute()) {
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            return $this->normalizeUploadPaths($rows);
        }
        return [];
    }

    // Get recent applications filtered by status (e.g., 'Pending')
    public function getRecentApplicationsByStatus($status, $limit = 10) {
        $sql = "SELECT a.*, p.firstName, p.lastName, s.title AS scholarship_title
                FROM applications a
                JOIN profile p ON a.student_id = p.profile_id
                JOIN scholarships s ON a.scholarship_id = s.scholarship_id
                WHERE LOWER(a.status) = LOWER(:status)
                ORDER BY a.applied_at DESC
                LIMIT :limit";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(':status', $status);
        $query->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

        if ($query->execute()) {
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            return $this->normalizeUploadPaths($rows);
        }
        return [];
    }

    // Normalize upload_file paths in result sets
    private function normalizeUploadPaths(array $rows): array {
        foreach ($rows as &$row) {
            if (isset($row['upload_file']) && $row['upload_file']) {
                $row['upload_file'] = $this->normalizePath($row['upload_file']);
            }
        }
        unset($row);
        return $rows;
    }

    // Ensure stored/legacy paths resolve to web path starting with uploads/applications/
    private function normalizePath(string $path): string {
        $p = str_replace('\\', '/', $path);
        $needle = 'uploads/applications/';
        $pos = strpos($p, $needle);
        if ($pos !== false) {
            $p = substr($p, $pos);
        }
        // Strip leading ./ or ../ or leading slashes
        while (strpos($p, '../') === 0 || strpos($p, './') === 0 || strpos($p, '/') === 0) {
            if (strpos($p, '../') === 0) { $p = substr($p, 3); continue; }
            if (strpos($p, './') === 0) { $p = substr($p, 2); continue; }
            if (strpos($p, '/') === 0) { $p = substr($p, 1); continue; }
        }
        return $p;
    }
}
