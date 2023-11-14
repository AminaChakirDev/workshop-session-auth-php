<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function login(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $data = array_map('trim', $_POST);
            $data = array_map('htmlentities', $data);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $userManager = new UserManager();
            $user = $userManager->selectOneByEmail($data['email']);

            if ($user && password_verify($data['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: /');
                exit();
            }
        }
        return $this->twig->render('User/login.html.twig');
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        header('Location: /');
        exit();
    }

    public function register(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //      @todo make some controls and if errors send them to the view
                    $data = $_POST;
                    $userManager = new UserManager();
            if ($userManager->insert($data)) {
                return $this->login();
            }
        }
                return $this->twig->render('User/register.html.twig');
    }

    public function access(int $id): string
    {
        if (!$this->user || $this->user['id'] !== $id) {
            echo 'Unauthorized access';
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }

        return $this->twig->render('User/profile.html.twig');
    }
}
