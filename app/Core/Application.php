<?php

namespace App\Core;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public static Application $app;
    public ?Controller $controller = null;
    public ?\App\Models\User $user = null;

    public function __construct()
    {
        self::$ROOT_DIR = dirname(__DIR__, 2);
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        
        $config = require self::$ROOT_DIR . '/app/Config/database.php';
        $this->db = new Database($config);
        
        $userId = $this->session->get('user');
        if ($userId) {
            $userModel = new \App\Models\User();
            $this->user = $userModel->findOne(['id' => $userId]);
        }
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function login(\App\Models\User $user)
    {
        $this->user = $user;
        $this->session->set('user', $user->id);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    public function getController(): ?Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode() ?: 500);
            echo $this->router->renderView('errors/_error', [
                'exception' => $e
            ]);
        }
    }
}
