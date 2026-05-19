<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

class Patient extends Model
{
    public int $id = 0;
    public string $national_id = '';
    public string $full_name = '';
    public string $dob = '';
    public string $gender = 'male';
    public string $phone = '';
    public string $address = '';

    public function rules(): array
    {
        return [
            'national_id' => ['required'],
            'full_name' => ['required'],
        ];
    }

    public function save()
    {
        $sql = "INSERT INTO patients (national_id, full_name, dob, gender, phone, address) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = Application::$app->db->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->bind_param("ssssss", 
            $this->national_id, 
            $this->full_name, 
            $this->dob, 
            $this->gender, 
            $this->phone, 
            $this->address
        );
        return $stmt->execute();
    }

    public function findAll()
    {
        $sql = "SELECT * FROM patients ORDER BY created_at DESC";
        $result = Application::$app->db->query($sql);
        $patients = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $patients[] = $row;
            }
        }
        return $patients;
    }
}
