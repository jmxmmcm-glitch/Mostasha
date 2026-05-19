<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Request;
use App\Models\MedicalRecord;
use App\Models\Visit;

class DoctorController extends Controller
{
    public function __construct()
    {
        if (Application::isGuest()) {
            Application::$app->response->redirect('/login');
            exit;
        }
        $role = Application::$app->user->role;
        if ($role !== 'admin' && $role !== 'doctor') {
            Application::$app->session->setFlash('error', 'صلاحيات وصول مرفوضة: هذه الصفحة للأطباء فقط.');
            Application::$app->response->redirect('/visits');
            exit;
        }
    }

    public function create(Request $request)
    {
        $visit_id = $_GET['visit_id'] ?? ($_POST['visit_id'] ?? null);
        
        if (!$visit_id) {
            Application::$app->session->setFlash('error', 'رقم الزيارة غير متوفر.');
            Application::$app->response->redirect('/visits');
            return;
        }

        $visitModel = new Visit();
        $visit = $visitModel->findOne($visit_id);

        if (!$visit) {
            Application::$app->session->setFlash('error', 'الزيارة غير موجودة.');
            Application::$app->response->redirect('/visits');
            return;
        }

        // Fetch triage data if exists
        $triageData = [];
        $res = Application::$app->db->query("SELECT * FROM triage WHERE visit_id = $visit_id ORDER BY created_at DESC LIMIT 1");
        if ($res && $res->num_rows > 0) {
            $triageData = $res->fetch_assoc();
        }

        if ($request->isPost()) {
            $record = new MedicalRecord();
            $data = $request->getBody();
            $data['doctor_id'] = Application::$app->user->role == 'admin' ? 1 : Application::$app->user->id; // Fallback for admin if missing
            if (isset(Application::$app->user->id)) {
                $data['doctor_id'] = Application::$app->user->id;
            }
            $record->loadData($data);
            
            if ($record->validate() && $record->save()) {
                // Determine next status
                $nextStatus = $data['next_status'] ?? 'discharged';
                $visitModel->updateStatus($visit_id, $nextStatus);
                
                // Generate Invoice Automatically
                require_once __DIR__ . '/../Models/Invoice.php';
                $invoice = new \App\Models\Invoice();
                $invoice->visit_id = $visit_id;
                $invoice->subtotal = 150.00; // Base ER Fee
                $invoice->vat_amount = 150.00 * 0.15; // 15% VAT
                $invoice->total_amount = $invoice->subtotal + $invoice->vat_amount;
                $invoice->save();
                
                Application::$app->session->setFlash('success', 'تم حفظ الملف الطبي وإصدار فاتورة تلقائية.');
                Application::$app->response->redirect('/visits');
                return;
            }
        }

        // When doctor opens the file, update status to in_treatment if it was waiting
        if ($visit['status'] == 'waiting' || $visit['status'] == 'triage') {
            $visitModel->updateStatus($visit_id, 'in_treatment');
            $visit['status'] = 'in_treatment';
        }

        return $this->render('doctor/create', [
            'visit' => $visit,
            'triage' => $triageData
        ]);
    }
}
