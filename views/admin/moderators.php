<div class="page-title">
    <h1>Manage Moderators</h1>
    <a class="button" href="index.php?controller=admin&action=addModerator">Add Moderator</a>
</div>

<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Created</th>
        <th>Action</th>
    </tr>

    <?php foreach ($moderators ?? [] as $mod): ?>
        <tr>
            <td><?= e($mod['name']) ?></td>
            <td><?= e($mod['email']) ?></td>
            <td><?= e($mod['created_at']) ?></td>
            <td>
                <form method="post" action="index.php?controller=admin&action=deleteModerator" onsubmit="return confirm('Delete this moderator?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($mod['id']) ?>">
                    <button class="danger small-btn" type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
