<?php
namespace App\Models;

use Config\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function Auth($email, $password)
    {
        $query = "SELECT username, role, user_id, password FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return [
                'username' => $user['username'],
                'role' => $user['role'],
                'user_id' => $user['user_id'],
            ];
        }
        return false;
    }

    public function Create($username, $email, $password)
    {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        return $stmt->execute();
    }
}
