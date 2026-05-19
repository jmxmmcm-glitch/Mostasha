<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Application;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Application::isGuest()) {
            Application::$app->response->redirect('/');
            return;
        }

        $this->setLayout('auth');
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $user = new User();
            $userRecord = $user->findOne(['username' => $data['username']]);
            
            if ($userRecord && password_verify($data['password'], $userRecord->password_hash)) {
                Application::$app->login($userRecord);
                Application::$app->response->redirect('/');
                return;
            }
            
            Application::$app->session->setFlash('error', 'اسم المستخدم أو كلمة المرور غير صحيحة');
        }

        return $this->render('login');
    }

    public function logout()
    {
        Application::$app->logout();
        Application::$app->response->redirect('/login');
    }
}
