<?php

function moderator_dashboard()
{
    require_role('moderator');

    view('moderator/dashboard', [
        'totalContents' => content_count_all(),
        'pendingRequests' => request_count_pending()
    ]);
}

function moderator_contents()
{
    require_role('moderator');

    view('moderator/contents', [
        'contents' => content_all(),
        'categories' => category_all()
    ]);
}

function moderator_uploadContent()
{
    require_role('moderator');

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        check_csrf();

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $categoryId = $_POST['category_id'] ?? '';
        $filePath = null;

        if ($title == '') {
            $errors[] = 'Title is required.';
        }

        if ($categoryId == '') {
            $errors[] = 'Category is required.';
        }

        list($ok, $result) = upload_file(
            $_FILES['media_file'],
            'contents',
            ['mp4', 'mkv', 'pdf', 'zip', 'rar', 'exe', 'jpg', 'jpeg', 'png'],
            50 * 1024 * 1024
        );

        if ($ok) {
            $filePath = $result;
        } else {
            $errors[] = $result;
        }

        if (empty($errors)) {
            content_create($title, $description, $filePath, $categoryId, current_user_id());
            flash('success', 'Content uploaded successfully.');
            redirect('index.php?controller=moderator&action=contents');
        }
    }

    view('moderator/content_form', [
        'errors' => $errors,
        'categories' => category_all()
    ]);
}

function moderator_deleteContent()
{






    require_role('moderator');
    check_csrf();

    $id = $_POST['id'] ?? 0;
    content_delete($id);

    flash('success', 'Content deleted.');
    redirect('index.php?controller=moderator&action=contents');
}

function moderator_requests()
{
    require_role('moderator');

    view('moderator/requests', [
        'requests' => request_all()
    ]);
}

function moderator_updateRequestStatus()
{
    require_role('moderator');
    check_csrf();

    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? 'pending';

    $success = request_update_status($id, $status);

    json_response(['success' => $success]);
}
