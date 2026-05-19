<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

class Visit extends Model
{
    public int $id = 0;
    public int $patient_id = 0;
    public string $arrival_time = '';
    public string $status = 'triage';
    public string $priority = 'non-urgent';

    public function rules(): array
    {
        return [
            'patient_id' => ['required']
        ];
    }

    public function save()
    {
        $sql = "INSERT INTO visits (patient_id, status, priority) 
                VALUES (?, ?, ?)";
        $stmt = Application::$app->db->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->bind_param("iss", 
            $this->patient_id, 
            $this->status, 
            $this->priority
        );
        return $stmt->execute();
    }

    public function findAllWithPatients()
    {
        $sql = "SELECT v.*, p.full_name, p.national_id 
                FROM visits v 
                JOIN patients p ON v.patient_id = p.id 
                ORDER BY v.arrival_time DESC";
        $result = Application::$app->db->query($sql);
        $visits = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $visits[] = $row;
            }
        }
        return $visits;
    }

    public function updateStatus(int $id, string $status)
    {
        $sql = "UPDATE visits SET status = ? WHERE id = ?";
        $stmt = Application::$app->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function findOne(int $id)
    {
        $sql = "SELECT v.*, p.full_name, p.national_id, p.dob, p.gender 
                FROM visits v JOIN patients p ON v.patient_id = p.id 
                WHERE v.id = ?";
        $stmt = Application::$app->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return false;
    }
}
