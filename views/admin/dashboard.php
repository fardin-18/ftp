<div class="page-title">
    <h1>Admin Dashboard</h1>
</div>

<div class="stats">
    <div class="stat"><strong><?= e($totalContents ?? 0) ?></strong><span>Total Contents</span></div>
    <div class="stat"><strong><?= e($totalCategories ?? 0) ?></strong><span>Categories</span></div>
    <div class="stat"><strong><?= e($totalModerators ?? 0) ?></strong><span>Moderators</span></div>
    <div class="stat"><strong><?= e($pendingRequests ?? 0) ?></strong><span>Pending Requests</span></div>
</div>
