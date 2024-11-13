<?php
namespace App\Controllers;

use App\Models\User;

class LoginController
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
            $this->login();
        } else {
            include 'App/Views/Login.php';
        }
    }

    private function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "All fields are required!";
            return;
        }

        $user = new User();
        $authenticated = $user->Auth($email, $password);

        if ($authenticated) {
            session_start();
            $_SESSION['username'] = $authenticated['username'];
            $_SESSION['role'] = $authenticated['role'];
            $_SESSION['user_id'] = $authenticated['user_id'];

            header('Location: /dashboard');
            exit;
        } else {
            echo "Login failed!";
        }
    }
}
