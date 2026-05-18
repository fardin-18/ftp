<section class="card auth-box wide">
    <h1>Upload Content</h1>

    <?php include BASE_PATH . '/views/layout/errors.php'; ?>

    <form method="post" enctype="multipart/form-data" id="contentForm">
        <?= csrf_field() ?>

        <label>Title</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description" required></textarea>

        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select category</option>
            <?php foreach ($categories ?? [] as $cat): ?>
                <option value="<?= e($cat['id']) ?>"><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Media File</label>
        <input type="file" name="media_file" required>

        <button type="submit">Upload</button>
    </form>
</section>
