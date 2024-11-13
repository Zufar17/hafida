<?php
namespace App\Controllers;

use App\Models\User;

class RegisterController
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
            $this->register();
        } else {
            include 'App/Views/Register.php';
        }
    }

    private function register()
    {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        var_dump($username, $email, $password);

        if (empty($username) || empty($email) || empty($password)) {
            echo "All fields are required!";
            return;
        }

        $password = password_hash($password, PASSWORD_BCRYPT);

        $user = new User();
        if ($user->Create($username, $email, $password)) {
            header('Location: /login');
        } else {
            echo "Registration failed!";
        }
    }

}
