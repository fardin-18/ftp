<h1>Content Requests</h1>

<table>
    <tr>
        <th>Title</th>
        <th>Category</th>
        <th>Message</th>
        <th>Status</th>
        <th>Created</th>
    </tr>

    <?php foreach ($requests ?? [] as $request): ?>
        <tr>
            <td><?= e($request['content_title']) ?></td>
            <td><?= e($request['category_requested']) ?></td>
            <td><?= e($request['message']) ?></td>
            <td>
                <select class="request-status" data-url="index.php?controller=admin&action=updateRequestStatus" data-id="<?= e($request['id']) ?>" data-token="<?= e(csrf_token()) ?>">
                    <?php foreach (['pending', 'fulfilled', 'rejected'] as $status): ?>
                        <option value="<?= e($status) ?>" <?= $request['status'] == $status ? 'selected' : '' ?>>
                            <?= e($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><?= e($request['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
