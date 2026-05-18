<?php if (empty($contents ?? [])): ?>
    <div class="empty-state">No content found.</div>
<?php endif; ?>

<?php foreach ($contents ?? [] as $item): ?>
    <article class="content-card">
        <h3><?= e($item['title']) ?></h3>
        <p><?= e($item['description']) ?></p>

        <div class="meta-line">
            <span class="badge">Category: <?= e($item['category_name'] ?? '') ?></span>
            <span class="badge">Downloads: <?= e($item['download_count'] ?? 0) ?></span>
        </div>

        <a class="button" href="index.php?controller=home&action=download&id=<?= e($item['id']) ?>">Download</a>
    </article>
<?php endforeach; ?>
