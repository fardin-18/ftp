<?php

function admin_dashboard()
{
    require_role('admin');

    view('admin/dashboard', [
        'totalContents' => content_count_all(),
        'totalCategories' => category_count_all(),
        'totalModerators' => user_count_moderators(),
        'pendingRequests' => request_count_pending()
    ]);
}

function admin_moderators()
{
    require_role('admin');

    view('admin/moderators', [
        'moderators' => user_all_moderators()
    ]);
}

function admin_addModerator()
{
    require_role('admin');

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        check_csrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name == '') {
            $errors[] = 'Name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if (user_email_exists($email)) {
            $errors[] = 'Email already exists.';
        }

        if (empty($errors)) {
            user_create($name, $email, $password, 'moderator');
            flash('success', 'Moderator added successfully.');
            redirect('index.php?controller=admin&action=moderators');
        }
    }

    view('admin/moderator_form', ['errors' => $errors]);
}





function admin_deleteModerator()
{
    require_role('admin');
    check_csrf();

    $id = $_POST['id'] ?? 0;
    user_delete_moderator($id);

    flash('success', 'Moderator deleted.');
    redirect('index.php?controller=admin&action=moderators');
}

function admin_contents()
{
    require_role('admin');

    view('admin/contents', [
        'contents' => content_all(),
        'categories' => category_all()
    ]);
}

function admin_uploadContent()
{
    require_role('admin');

    admin_save_content(null);
}

function admin_editContent()
{
    require_role('admin');

    $id = $_GET['id'] ?? 0;
    admin_save_content($id);
}

function admin_save_content($id = null)
{
    $errors = [];
    $content = null;

    if ($id != null) {
        $content = content_find($id);

        if (!$content) {
            flash('error', 'Content not found.');
            redirect('index.php?controller=admin&action=contents');
        }
    }

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

        if ($id == null || !empty($_FILES['media_file']['name'])) {
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
        }

        if (empty($errors)) {
            if ($id == null) {
                content_create($title, $description, $filePath, $categoryId, current_user_id());
                flash('success', 'Content uploaded successfully.');
            } else {
                content_update($id, $title, $description, $categoryId, $filePath);
                flash('success', 'Content updated successfully.');
            }

            redirect('index.php?controller=admin&action=contents');
        }
    }

    view('admin/content_form', [
        'errors' => $errors,
        'content' => $content,
        'categories' => category_all()
    ]);
}

function admin_deleteContent()
{
    require_role('admin');
    check_csrf();

    $id = $_POST['id'] ?? 0;
    content_delete($id);

    flash('success', 'Content deleted.');
    redirect('index.php?controller=admin&action=contents');
}

function admin_requests()
{
    require_role('admin');

    view('admin/requests', [
        'requests' => request_all()
    ]);
}

function admin_updateRequestStatus()
{
    require_role('admin');
    check_csrf();

    $id = $_POST['id'] ?? 0;
    $status = $_POST['status'] ?? 'pending';

    $success = request_update_status($id, $status);

    json_response(['success' => $success]);
}
