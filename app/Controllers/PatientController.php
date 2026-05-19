<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    public function __construct()
    {
        if (Application::isGuest()) {
            Application::$app->response->redirect('/login');
            exit;
        }
    }

    public function index()
    {
        $patientModel = new Patient();
        $patients = $patientModel->findAll();
        
        return $this->render('patients/index', [
            'patients' => $patients
        ]);
    }

    public function create(Request $request)
    {
        $role = Application::$app->user->role;
        if ($role !== 'admin' && $role !== 'receptionist') {
            Application::$app->session->setFlash('error', 'صلاحيات وصول مرفوضة: إضافة المرضى لموظفي الاستقبال فقط.');
            Application::$app->response->redirect('/patients');
            return;
        }

        if ($request->isPost()) {
            $patient = new Patient();
            $patient->loadData($request->getBody());
            
            if ($patient->validate() && $patient->save()) {
                Application::$app->session->setFlash('success', 'تم تسجيل المريض بنجاح.');
                Application::$app->response->redirect('/patients');
                return;
            }
        }

        return $this->render('patients/create');
    }

    public function view(Request $request)
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            Application::$app->response->redirect('/patients');
            return;
        }

        $patientModel = new Patient();
        $patients = $patientModel->findAll();
        $patient = null;
        foreach ($patients as $p) {
            if ($p['id'] == $id) {
                $patient = $p;
                break;
            }
        }

        if (!$patient) {
            Application::$app->response->redirect('/patients');
            return;
        }

        $visits = [];
        $res = Application::$app->db->query("
            SELECT v.*, 
                   t.blood_pressure, t.heart_rate, t.temperature, t.notes as triage_notes,
                   m.diagnosis, m.treatment_plan, m.prescriptions, m.created_at as medical_date,
                   d.full_name as doctor_name
            FROM visits v
            LEFT JOIN triage t ON v.id = t.visit_id
            LEFT JOIN medical_records m ON v.id = m.visit_id
            LEFT JOIN users d ON m.doctor_id = d.id
            WHERE v.patient_id = $id
            ORDER BY v.arrival_time DESC
        ");

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $visits[] = $row;
            }
        }

        return $this->render('patients/view', [
            'patient' => $patient,
            'visits' => $visits
        ]);
    }
}
