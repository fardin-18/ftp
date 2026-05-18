<?php

function request_create($requesterIp, $title, $category, $message)
{
    $sql = "INSERT INTO content_requests(requester_ip, content_title, category_requested, message, status)
            VALUES(?, ?, ?, ?, 'pending')";

    $stmt = db()->prepare($sql);

    return $stmt->execute([$requesterIp, $title, $category, $message]);
}

function request_all()
{
    $sql = "SELECT * FROM content_requests ORDER BY created_at DESC";
    $stmt = db()->query($sql);

    return $stmt->fetchAll();
}

function request_update_status($id, $status)
{
    $allowedStatus = ['pending', 'fulfilled', 'rejected'];

    if (!in_array($status, $allowedStatus)) {
        return false;
    }

    $sql = "UPDATE content_requests SET status = ? WHERE id = ?";
    $stmt = db()->prepare($sql);

    return $stmt->execute([$status, $id]);
}

function request_count_pending()
{
    $sql = "SELECT COUNT(*) AS total FROM content_requests WHERE status = 'pending'";
    $stmt = db()->query($sql);
    $row = $stmt->fetch();

    return $row['total'];
}
