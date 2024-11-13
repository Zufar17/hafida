<?php
namespace App\Controllers;

class AddForumController
{
    public function index()
    {
        session_start();
        $role = $_SESSION['role'] ?? null;
        $username = $_SESSION['username'] ?? 'Guest';

        if (!$role) {
            header('Location: /login');
            exit;
        }

        // Redirect to specific dashboard based on role
        switch ($role) {
            case 'member':
                $view = 'Member/AddForum.php';
                break;
            case 'psikolog':
                $view = 'Psikolog/AddForum.php';
                break;
            default:
                header('Location: /');
                exit;
        }

        include 'App/Views/Dashboard/'.$view;
    }
}
