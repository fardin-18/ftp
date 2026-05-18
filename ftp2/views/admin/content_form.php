<section class="card auth-box wide">
    <h1><?= empty($content) ? 'Upload Content' : 'Edit Content' ?></h1>

    <?php include BASE_PATH . '/views/layout/errors.php'; ?>

    <form method="post" enctype="multipart/form-data" id="contentForm">
        <?= csrf_field() ?>

        <label>Title</label>
        <input type="text" name="title" value="<?= e($content['title'] ?? '') ?>" required>

        <label>Description</label>
        <textarea name="description" required><?= e($content['description'] ?? '') ?></textarea>

        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select category</option>
            <?php foreach ($categories ?? [] as $cat): ?>
                <option value="<?= e($cat['id']) ?>" <?= isset($content['category_id']) && $content['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Media File</label>
        <input type="file" name="media_file" <?= empty($content) ? 'required' : '' ?>>

        <button type="submit">Save Content</button>
    </form>
</section>
