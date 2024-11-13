<?php
namespace App\Controllers;

class ProfileController
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
                $view = 'Member/Profile.php';
                break;
            case 'psikolog':
                $view = 'Psikolog/Profile.php';
                break;
            case 'owner':
                $view = 'Owner/Profile.php';
                break;
            default:
                header('Location: /');
                exit;
        }

        include 'App/Views/Dashboard/'.$view;
    }
}
