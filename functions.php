<?php
function validateEmail($email) {
    if (empty($email)) {
        return "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email.";
    }
    return '';
}

function validatePassword($password) {
    if (empty($password)) {
        return "Password is required.";
    }
    return '';
}

// Function to authenticate user by email and password
function authenticateUser($email, $password, $users) {
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            if ($user['password'] === $password) {
                return true;
            } else {
                return "Invalid email or password.";
            }
        }
    }
    return "Invalid email or password.";
}

function guard() {
    if (isset($_SESSION['email'])) {
        header('Location: dashboard.php');
        exit;
    }
}
?>
