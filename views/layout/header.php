<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP Media FTP Server</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a class="brand" href="index.php">ISP Media Server</a>

        
        <nav class="nav-links">
            <a href="index.php">Home</a>

            <?php if (current_role() == 'admin'): ?>
                <a href="index.php?controller=admin&action=dashboard">Admin Dashboard</a>
                <a href="index.php?controller=admin&action=moderators">Moderators</a>
                <a href="index.php?controller=admin&action=contents">Contents</a>
                <a href="index.php?controller=admin&action=requests">Requests</a>
            <?php elseif (current_role() == 'moderator'): ?>
                <a href="index.php?controller=moderator&action=dashboard">Moderator Dashboard</a>
                <a href="index.php?controller=moderator&action=contents">Contents</a>
                <a href="index.php?controller=moderator&action=requests">Requests</a>
            <?php endif; ?>

            <?php if (is_logged_in()): ?>
                <a href="index.php?controller=auth&action=profile">Profile</a>
                <a class="nav-btn" href="index.php?controller=auth&action=logout">Logout</a>
            <?php else: ?>
                <a class="nav-btn" href="index.php?controller=auth&action=login">Login</a>
                <a class="nav-btn register-btn" href="index.php?controller=auth&action=register">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="container main">
    <?php if ($msg = flash('success')): ?>
        <div class="alert success"><?= e($msg) ?></div>
    <?php endif; ?>

    <?php if ($msg = flash('error')): ?>
        <div class="alert error"><?= e($msg) ?></div>
    <?php endif; ?>
