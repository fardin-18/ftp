<?php

function home_index()
{
    view('home/index', [
        'categories' => category_top_level(),
        'allCategories' => category_all(),
        'contents' => content_latest(12)
    ]);
}

function home_category()
{
    $id = $_GET['id'] ?? 0;
    $category = category_find($id);

    if (!$category) {
        flash('error', 'Category not found.');
        redirect('index.php');
    }

    $categoryIds = category_ids_with_children($id);
    $contents = content_by_category_ids($categoryIds);

    view('home/category', [
        'category' => $category,
        'categories' => category_top_level(),
        'subCategories' => category_children($id),
        'contents' => $contents
    ]);
}

function home_search()
{
    $keyword = trim($_GET['q'] ?? '');
    $categoryId = trim($_GET['category_id'] ?? '');

    $contents = content_search($keyword, $categoryId);

    json_response([
        'success' => true,
        'contents' => $contents
    ]);
}

function home_requestContent()
{
    check_csrf();

    $title = trim($_POST['content_title'] ?? '');
    $category = trim($_POST['category_requested'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($title == '') {
        json_response([
            'success' => false,
            'message' => 'Content title is required.'
        ]);
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    request_create($ip, $title, $category, $message);

    json_response([
        'success' => true,
        'message' => 'Your request has been submitted.'
    ]);
}

function home_download()
{
    $id = $_GET['id'] ?? 0;
    $content = content_find($id);

    if (!$content) {
        flash('error', 'Content not found.');
        redirect('index.php');
    }

    $filePath = PUBLIC_PATH . '/' . $content['file_path'];

    if (!file_exists($filePath)) {
        flash('error', 'File does not exist on the server.');
        redirect('index.php');
    }

    content_increment_download($id);

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Content-Length: ' . filesize($filePath));

    readfile($filePath);
    exit;
}
