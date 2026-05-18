<?php
$category = $category ?? ['name' => ''];

?>
<section class="card">
    <h1><?= e($category['name']) ?></h1>

    <?php if (!empty($subCategories)): ?>
        <h3>Sub-categories</h3>
        <div class="category-list">
            <?php foreach ($subCategories as $sub): ?>
                <a class="category-pill" href="index.php?controller=home&action=category&id=<?= e($sub['id']) ?>">
                    <?= e($sub['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="card">
    <h2>Contents</h2>
    <div class="content-grid">
        <?php include BASE_PATH . '/views/home/partials_content_list.php'; ?>
    </div>
</section>



