<div class="page-title">
    <h1>Manage Contents</h1>
    <a class="button" href="index.php?controller=moderator&action=uploadContent">Upload Content</a>
</div>




<table>
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Uploader</th>
        <th>Downloads</th>
        <th>Action</th>
    </tr>

    <?php foreach ($contents ?? [] as $item): ?>
        <tr>
            <td><?= e($item['title']) ?></td>
            <td><?= e($item['category_name']) ?></td>
            <td><?= e($item['uploader_name']) ?></td>
            <td><?= e($item['download_count']) ?></td>
            <td>
                <form method="post" action="index.php?controller=moderator&action=deleteContent" onsubmit="return confirm('Delete this content?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                    <button class="danger small-btn" type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
