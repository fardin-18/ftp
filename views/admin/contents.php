<div class="page-title">
    <h1>Manage Contents</h1>
    <a class="button" href="index.php?controller=admin&action=uploadContent">Upload Content</a>
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
            <td class="actions">
                <a class="small-btn" href="index.php?controller=admin&action=editContent&id=<?= e($item['id']) ?>">Edit</a>

                <form method="post" action="index.php?controller=admin&action=deleteContent" onsubmit="return confirm('Delete this content?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                    <button class="danger small-btn" type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
