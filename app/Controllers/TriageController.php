<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Request;
use App\Models\Triage;
use App\Models\Visit;

class TriageController extends Controller
{
    public function __construct()
    {
        if (Application::isGuest()) {
            Application::$app->response->redirect('/login');
            exit;
        }
        $role = Application::$app->user->role;
        if ($role !== 'admin' && $role !== 'nurse') {
            Application::$app->session->setFlash('error', 'صلاحيات وصول مرفوضة: هذه الصفحة للتمريض فقط.');
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

        if ($request->isPost()) {
            $triage = new Triage();
            $data = $request->getBody();
            // In a real app we check role, here we just assign the logged in user
            $data['nurse_id'] = Application::$app->user->id; 
            $triage->loadData($data);
            
            if ($triage->validate() && $triage->save()) {
                // Update visit status to 'waiting' (for doctor)
                $visitModel->updateStatus($visit_id, 'waiting');
                
                Application::$app->session->setFlash('success', 'تم حفظ التقييم الحيوي وتوجيه المريض لطبيب الطوارئ.');
                Application::$app->response->redirect('/visits');
                return;
            }
        }

        return $this->render('triage/create', [
            'visit' => $visit
        ]);
    }
}
