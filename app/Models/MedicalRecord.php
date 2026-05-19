<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

class MedicalRecord extends Model
{
    public int $visit_id = 0;
    public int $doctor_id = 0;
    public string $diagnosis = '';
    public string $treatment_plan = '';
    public string $prescriptions = '';

    public function rules(): array
    {
        return [
            'visit_id' => ['required'],
            'diagnosis' => ['required']
        ];
    }

    public function save()
    {
        // Ensure table exists
        $sqlCreate = "CREATE TABLE IF NOT EXISTS medical_records (
            id INT AUTO_INCREMENT PRIMARY KEY,
            visit_id INT NOT NULL,
            doctor_id INT NOT NULL,
            diagnosis TEXT,
            treatment_plan TEXT,
            prescriptions TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (visit_id) REFERENCES visits(id) ON DELETE CASCADE,
            FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE RESTRICT
        )";
        Application::$app->db->query($sqlCreate);

        $sql = "INSERT INTO medical_records (visit_id, doctor_id, diagnosis, treatment_plan, prescriptions) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = Application::$app->db->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->bind_param("iisss", 
            $this->visit_id, 
            $this->doctor_id, 
            $this->diagnosis, 
            $this->treatment_plan, 
            $this->prescriptions
        );
        return $stmt->execute();
    }
}
