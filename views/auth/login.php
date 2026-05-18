<section class="card auth-box">
    <h1>Admin / Moderator Login</h1>

    <?php include BASE_PATH . '/views/layout/errors.php'; ?>

    <form method="post">
        <?= csrf_field() ?>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        
        <input type="password" name="password" required>

        <button type="submit">Login</button>
        <p class="small">No account? <a style="text-decoration: none; color: #0559b3;" href="index.php?controller=auth&action=register">Register here</a></p>
    </form>

    
</section>
