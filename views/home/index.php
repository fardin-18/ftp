<?php $allCategories = $allCategories ?? []; ?>
<section class="hero card">
    <h1>ISP Media Server</h1>
    <p>Browse and download movies, software, TV series, games and other media content from one clean place.</p>

    <div class="hero-actions">
        <a class="button light" href="#contentList">Browse Contents</a>
        <a class="button yellow" href="#requestForm">Request Content</a>
    </div>
</section>

<section class="card">
    <div class="section-heading">
        <h2>Categories</h2>
    </div>

    <div class="category-list">
        <?php foreach ($allCategories  as $cat): ?>
            <a class="category-pill" href="index.php?controller=home&action=category&id=<?= e($cat['id']) ?>">
                <?= e($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="card">
    <div class="section-heading">
        <h2>Search Contents</h2>
    </div>

    <form id="searchForm" class="search-form">
        <input type="text" id="searchInput" placeholder="Search by title or description">

        <select id="categoryFilter">
            <option value="">All categories</option>
            <?php foreach ($allCategories ?? [] as $cat): ?>
                <option value="<?= e($cat['id']) ?>"><?= e($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" style="height: 45px; margin-top: -1px;">Search</button>
    </form>
</section>

<section class="card">
    <div class="section-heading">
        <h2>Latest Contents</h2>
    </div>

    <div id="contentList" class="content-grid">
        <?php include BASE_PATH . '/views/home/partials_content_list.php'; ?>
    </div>
</section>

<section class="card">
    <div class="section-heading">
        <h2>Request New Content</h2>
    </div>

    <form id="requestForm" class="grid-form" method="post" action="index.php?controller=home&action=requestContent">
        <?= csrf_field() ?>

        <input type="text" name="content_title" placeholder="Requested content title" required>
        <input type="text" name="category_requested" placeholder="Category, e.g., Movies">
        <textarea name="message" placeholder="Additional message"></textarea>

        <button type="submit">Submit Request</button>
    </form>

    <p id="requestMessage" class="small"></p>
</section>
