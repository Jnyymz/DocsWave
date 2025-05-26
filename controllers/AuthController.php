<?php
require_once __DIR__ . '/../core/model.php';
session_start();

class AuthController
{
    private $userModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
    }

    public function login($username, $password)
    {
        $user = $this->userModel->verifyCredentials($username, $password);

        // Debug: Output user data
        // Remove or comment this out in production!
        // echo '<pre>'; var_dump($user); echo '</pre>'; exit;

        if ($user) {
            if ($user['suspended']) {
                return ['error' => 'Account is suspended.'];
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            return ['success' => true, 'role' => $user['role']];
        }
        return ['error' => 'Invalid credentials.'];
    }

    public function register($username, $password, $email, $role = 'user')
    {
        if ($this->userModel->findByUsername($username)) {
            return ['error' => 'Username already exists.'];
        }
        $success = $this->userModel->create($username, $password, $email, $role);
        if ($success) {
            return ['success' => true];
        }
        return ['error' => 'Registration failed.'];
    }
}
