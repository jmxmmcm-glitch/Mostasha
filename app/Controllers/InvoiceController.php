<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Application;
use App\Core\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function __construct()
    {
        // For commercial release, maybe only Accountants or Admins can see this.
        if (Application::isGuest() || Application::$app->user->role !== 'admin') {
            Application::$app->session->setFlash('error', 'هذه الصفحة للإدارة والمحاسبة فقط.');
            Application::$app->response->redirect('/');
            exit;
        }
    }

    public function index()
    {
        $invoiceModel = new Invoice();
        $invoices = $invoiceModel->findAll();
        
        return $this->render('invoices/index', [
            'invoices' => $invoices
        ]);
    }

    public function pay(Request $request)
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $invoiceModel = new Invoice();
            $invoiceModel->markAsPaid($id);
            Application::$app->session->setFlash('success', 'تم سداد الفاتورة بنجاح.');
        }
        Application::$app->response->redirect('/invoices');
    }
}
