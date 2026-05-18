</main>

<footer class="footer">
    <div class="container">
        <p><strong>ISP Media FTP Server</strong></p>
        <p class="small">Basic PHP MVC Project</p>

        <?php if (!is_logged_in()): ?>
            <p class="small">
                <a href="index.php?controller=auth&action=login">Staff Login</a> |
                <a href="index.php?controller=auth&action=register">Staff Registration</a>
            </p>
        <?php endif; ?>
    </div>
    
</footer>

<script src="assets/js/app.js"></script>
</body>
</html>
