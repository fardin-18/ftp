<section class="card auth-box wide">
    <h1>My Profile</h1>

    <?php include BASE_PATH . '/views/layout/errors.php'; ?>

    <?php if (!empty($user['profile_picture'])): ?>
        <img class="profile-picture" src="<?= e($user['profile_picture']) ?>" alt="Profile picture">
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        

        <label>Name</label>
        <input type="text" name="name" value="<?= e($user['name'] ?? "name") ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= e($user['email'] ?? "email") ?>" required>

        <label>Profile Picture</label>
        <input type="file" name="profile_picture">

        <hr>

        <label>Current Password</label>
        <input type="password" name="current_password">

        <label>New Password</label>
        <input type="password" name="new_password">

        <button type="submit">Update Profile</button>
    </form>
</section>
