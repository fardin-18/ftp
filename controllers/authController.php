<?php

function auth_register()
{


    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        check_csrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? '';

        if ($name == '') {
            $errors[] = 'Name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($password != $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        if ($role != 'admin' && $role != 'moderator') {
            $errors[] = 'Invalid role.';
        }

        if (user_email_exists($email)) {
            $errors[] = 'Email already exists.';
        }

        if (empty($errors)) {
            user_create($name, $email, $password, $role);
            flash('success', 'Registration successful. Please login.');
            redirect('index.php?controller=auth&action=login');
        }
    }

    

    view('auth/register', ['errors' => $errors]);
}

function auth_login()
{
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        check_csrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = user_find_by_email($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                redirect('index.php?controller=admin&action=dashboard');
            } else {
                redirect('index.php?controller=moderator&action=dashboard');
            }
        }

        $errors[] = 'Invalid email or password.';
    }

    view('auth/login', ['errors' => $errors]);
}

function auth_profile()
{
    require_login();

    $user = user_find(current_user_id());
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        check_csrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $profilePicture = null;

        if ($name == '') {
            $errors[] = 'Name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }

        if (user_email_exists($email, current_user_id())) {
            $errors[] = 'Email already exists.';
        }

        if (!empty($_FILES['profile_picture']['name'])) {
            list($ok, $result) = upload_file(
                $_FILES['profile_picture'],
                'profiles',
                ['jpg', 'jpeg', 'png'],
                2 * 1024 * 1024
            );

            if ($ok) {
                $profilePicture = $result;
            } else {
                $errors[] = $result;
            }
        }

        if ($newPassword != '') {
            if (!password_verify($currentPassword, $user['password_hash'])) {
                $errors[] = 'Current password is incorrect.';
            }

            if (strlen($newPassword) < 8) {
                $errors[] = 'New password must be at least 8 characters.';
            }
        }

        if (empty($errors)) {
            user_update_profile(current_user_id(), $name, $email, $profilePicture);

            if ($newPassword != '') {
                user_update_password(current_user_id(), $newPassword);
            }

            $_SESSION['name'] = $name;
            flash('success', 'Profile updated successfully.');
            redirect('index.php?controller=auth&action=profile');
        }
    }

    view('auth/profile', ['user' => $user, 'errors' => $errors]);
}

function auth_logout()
{
    session_destroy();
    redirect('index.php');
}
