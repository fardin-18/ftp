<section class="card auth-box">
    <h1>Add Moderator</h1>

    <?php include BASE_PATH . '/views/layout/errors.php'; ?>

    <form method="post">
        <?= csrf_field() ?>

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Save Moderator</button>
    </form>
</section>
