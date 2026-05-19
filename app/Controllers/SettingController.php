<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function __construct()
    {
        if (Application::isGuest() || Application::$app->user->role !== 'admin') {
            Application::$app->session->setFlash('error', 'هذه الصفحة مخصصة لمدير النظام فقط.');
            Application::$app->response->redirect('/');
            exit;
        }
    }

    public function index(Request $request)
    {
        $setting = new Setting();
        $setting->loadAll();

        if ($request->isPost()) {
            $data = $request->getBody();
            if ($setting->updateAll($data)) {
                Application::$app->session->setFlash('success', 'تم حفظ إعدادات المركز بنجاح.');
                Application::$app->response->redirect('/settings');
                return;
            }
        }

        return $this->render('settings/index', [
            'setting' => $setting
        ]);
    }
}
