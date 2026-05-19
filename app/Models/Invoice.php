<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

class Invoice extends Model
{
    public int $visit_id = 0;
    public float $subtotal = 0;
    public float $vat_amount = 0;
    public float $total_amount = 0;

    public function rules(): array
    {
        return [];
    }

    public function initTable()
    {
        $sqlCreate = "CREATE TABLE IF NOT EXISTS invoices (
            id INT AUTO_INCREMENT PRIMARY KEY,
            visit_id INT NOT NULL,
            invoice_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            subtotal DECIMAL(10,2) NOT NULL,
            vat_amount DECIMAL(10,2) NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
            FOREIGN KEY (visit_id) REFERENCES visits(id) ON DELETE CASCADE
        )";
        Application::$app->db->query($sqlCreate);
    }

    public function save()
    {
        $this->initTable();
        $sql = "INSERT INTO invoices (visit_id, subtotal, vat_amount, total_amount) VALUES (?, ?, ?, ?)";
        $stmt = Application::$app->db->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->bind_param("iddd", 
            $this->visit_id, 
            $this->subtotal, 
            $this->vat_amount, 
            $this->total_amount
        );
        return $stmt->execute();
    }
    
    public function findAll()
    {
        $this->initTable();
        $sql = "SELECT i.*, p.full_name, p.national_id, v.arrival_time 
                FROM invoices i 
                JOIN visits v ON i.visit_id = v.id 
                JOIN patients p ON v.patient_id = p.id 
                ORDER BY i.invoice_date DESC";
        $result = Application::$app->db->query($sql);
        $invoices = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $invoices[] = $row;
            }
        }
        return $invoices;
    }

    public function markAsPaid(int $id)
    {
        $sql = "UPDATE invoices SET status = 'paid' WHERE id = ?";
        $stmt = Application::$app->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
