<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

class Triage extends Model
{
    public int $visit_id = 0;
    public int $nurse_id = 0;
    public string $blood_pressure = '';
    public string $heart_rate = '';
    public string $temperature = '';
    public string $notes = '';

    public function rules(): array
    {
        return [
            'visit_id' => ['required'],
            'nurse_id' => ['required']
        ];
    }

    public function save()
    {
        $sql = "INSERT INTO triage (visit_id, nurse_id, blood_pressure, heart_rate, temperature, notes) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = Application::$app->db->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->bind_param("iissss", 
            $this->visit_id, 
            $this->nurse_id, 
            $this->blood_pressure, 
            $this->heart_rate, 
            $this->temperature,
            $this->notes
        );
        return $stmt->execute();
    }
}
