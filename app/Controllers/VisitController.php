<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Request;
use App\Models\Visit;
use App\Models\Patient;

class VisitController extends Controller
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
        $visitModel = new Visit();
        $visits = $visitModel->findAllWithPatients();
        
        return $this->render('visits/index', [
            'visits' => $visits
        ]);
    }

    public function create(Request $request)
    {
        $role = Application::$app->user->role;
        if ($role !== 'admin' && $role !== 'receptionist') {
            Application::$app->session->setFlash('error', 'صلاحيات وصول مرفوضة: تسجيل الزيارات لموظفي الاستقبال فقط.');
            Application::$app->response->redirect('/visits');
            return;
        }

        if ($request->isPost()) {
            $visit = new Visit();
            $visit->loadData($request->getBody());
            
            if ($visit->validate() && $visit->save()) {
                Application::$app->session->setFlash('success', 'تم فتح ملف الطوارئ بنجاح وتحويل المريض للفرز.');
                Application::$app->response->redirect('/visits');
                return;
            }
        }

        $patient_id = $_GET['patient_id'] ?? null;
        $patient = null;
        if ($patient_id) {
            $patientModel = new Patient();
            $patients = $patientModel->findAll();
            foreach ($patients as $p) {
                if ($p['id'] == $patient_id) {
                    $patient = $p;
                    break;
                }
            }
        }

        return $this->render('visits/create', [
            'patient' => $patient,
            'patient_id' => $patient_id
        ]);
    }
}
