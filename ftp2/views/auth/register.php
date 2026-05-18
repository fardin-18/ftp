<section class="card auth-box">
    <h1>Admin / Moderator Login</h1>

    <?php include BASE_PATH . '/views/layout/errors.php'; ?>

    <form method="post" id="registerForm">
        <?= csrf_field() ?>

        <label>Name</label>
        <input type="text" name="name" required>
        

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" id="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" id="confirmPassword" required>

        <label>Role</label>
        <select name="role" required>
            <option value="">Select role</option>
            <option value="admin">Admin</option>
            <option value="moderator">Moderator</option>
        </select>

        <button type="submit">Register</button>
        <p class="small">Already have an account? <a style="text-decoration: none; color: #0559b3;" href="index.php?controller=auth&action=login">Login here</a></p>
    </form>
</section>
