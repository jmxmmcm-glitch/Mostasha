<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

class User extends Model
{
    public int $id = 0;
    public string $username = '';
    public string $password_hash = '';
    public string $role = '';
    public string $full_name = '';

    public function rules(): array
    {
        return [
            'username' => ['required'],
            'password' => ['required']
        ];
    }

    public function findOne(array $where)
    {
        $tableName = "users";
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn($attr) => "$attr = ?", $attributes));
        
        $stmt = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $sql");
        if (!$stmt) return false;

        $types = str_repeat('s', count($attributes));
        $values = array_values($where);
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->loadData($row);
            return $this;
        }
        return false;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $result = Application::$app->db->query($sql);
        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    public function save()
    {
        $sql = "INSERT INTO users (username, password_hash, role, full_name) 
                VALUES (?, ?, ?, ?)";
        $stmt = Application::$app->db->prepare($sql);
        if (!$stmt) return false;
        
        $hashed_password = password_hash($this->password_hash, PASSWORD_DEFAULT);
        
        $stmt->bind_param("ssss", 
            $this->username, 
            $hashed_password, 
            $this->role, 
            $this->full_name
        );
        return $stmt->execute();
    }
}
