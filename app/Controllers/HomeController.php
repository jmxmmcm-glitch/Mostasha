<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;

class HomeController extends Controller
{
    public function index()
    {
        if (Application::isGuest()) {
            Application::$app->response->redirect('/login');
            return;
        }

        $db = Application::$app->db;
        
        $res = $db->query("SELECT count(*) as total FROM visits WHERE status IN ('triage', 'waiting', 'in_treatment')");
        $active_cases = $res ? $res->fetch_assoc()['total'] : 0;

        $res = $db->query("SELECT count(*) as total FROM visits WHERE status = 'triage'");
        $triage_cases = $res ? $res->fetch_assoc()['total'] : 0;

        $res = $db->query("SELECT count(*) as total FROM visits WHERE status = 'in_treatment'");
        $in_treatment = $res ? $res->fetch_assoc()['total'] : 0;
        
        $total_beds = 15;
        $available_beds = $total_beds - $in_treatment;
        if ($available_beds < 0) $available_beds = 0;

        $params = [
            'title' => 'لوحة التحكم الرئيسية',
            'user' => Application::$app->user,
            'active_cases' => $active_cases,
            'triage_cases' => $triage_cases,
            'available_beds' => $available_beds
        ];
        return $this->render('home', $params);
    }
}
