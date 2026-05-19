<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        if (Application::isGuest() || Application::$app->user->role !== 'admin') {
            Application::$app->session->setFlash('error', 'غير مصرح لك بالدخول لهذه الصفحة.');
            Application::$app->response->redirect('/');
            exit;
        }
    }

    public function index()
    {
        $userModel = new User();
        $users = $userModel->findAll();
        
        return $this->render('users/index', [
            'users' => $users
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isPost()) {
            $user = new User();
            $data = $request->getBody();
            // Map password to password_hash property for the model
            $data['password_hash'] = $data['password']; 
            
            $user->loadData($data);
            
            // Check if username exists
            $exists = $user->findOne(['username' => $data['username']]);
            if ($exists) {
                Application::$app->session->setFlash('error', 'اسم المستخدم موجود مسبقاً.');
            } else {
                if ($user->validate() && $user->save()) {
                    Application::$app->session->setFlash('success', 'تم إضافة الموظف بنجاح.');
                    Application::$app->response->redirect('/users');
                    return;
                }
            }
        }

        return $this->render('users/create');
    }
}
