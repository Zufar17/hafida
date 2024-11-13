<?php
namespace App\Controllers;

class DashboardController
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
                $view = 'Member/Member.php';
                break;
            case 'psikolog':
                $view = 'Psikolog/Psikolog.php';
                break;
            case 'owner':
                $view = 'Owner/Owner.php';
                break;
            default:
                header('Location: /');
                exit;
        }

        include 'App/Views/Dashboard/'.$view;
    }
}
