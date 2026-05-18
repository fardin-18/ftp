<?php

function content_latest($limit = 8)
{
    $sql = "SELECT c.*, cat.name AS category_name, u.name AS uploader_name
            FROM contents c
            JOIN categories cat ON cat.id = c.category_id
            JOIN users u ON u.id = c.uploader_id
            ORDER BY c.uploaded_at DESC
            LIMIT ?";

    $stmt = db()->prepare($sql);
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function content_all($keyword = '', $categoryId = '')
{
    $sql = "SELECT c.*, cat.name AS category_name, u.name AS uploader_name
            FROM contents c
            JOIN categories cat ON cat.id = c.category_id
            JOIN users u ON u.id = c.uploader_id
            WHERE 1 = 1";

    $values = [];

    if ($keyword != '') {
        $sql .= " AND (c.title LIKE ? OR c.description LIKE ?)";
        $values[] = '%' . $keyword . '%';
        $values[] = '%' . $keyword . '%';
    }

    if ($categoryId != '') {
        $sql .= " AND c.category_id = ?";
        $values[] = $categoryId;
    }

    $sql .= " ORDER BY c.uploaded_at DESC";

    $stmt = db()->prepare($sql);
    $stmt->execute($values);

    return $stmt->fetchAll();
}

function content_by_category_ids($ids)
{
    if (empty($ids)) {
        return [];
    }

    $questionMarks = implode(',', array_fill(0, count($ids), '?'));

    $sql = "SELECT c.*, cat.name AS category_name, u.name AS uploader_name
            FROM contents c
            JOIN categories cat ON cat.id = c.category_id
            JOIN users u ON u.id = c.uploader_id
            WHERE c.category_id IN ($questionMarks)
            ORDER BY c.uploaded_at DESC";

    $stmt = db()->prepare($sql);
    $stmt->execute($ids);

    return $stmt->fetchAll();
}

function content_search($keyword, $categoryId = '')
{
    return content_all($keyword, $categoryId);
}

function content_find($id)
{
    $sql = "SELECT * FROM contents WHERE id = ? LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}

function content_create($title, $description, $filePath, $categoryId, $uploaderId)
{
    $sql = "INSERT INTO contents(title, description, file_path, category_id, uploader_id)
            VALUES(?, ?, ?, ?, ?)";

    $stmt = db()->prepare($sql);

    return $stmt->execute([$title, $description, $filePath, $categoryId, $uploaderId]);
}

function content_update($id, $title, $description, $categoryId, $filePath = null)
{
    if ($filePath != null) {
        $sql = "UPDATE contents
                SET title = ?, description = ?, category_id = ?, file_path = ?
                WHERE id = ?";

        $stmt = db()->prepare($sql);
        return $stmt->execute([$title, $description, $categoryId, $filePath, $id]);
    }

    $sql = "UPDATE contents
            SET title = ?, description = ?, category_id = ?
            WHERE id = ?";

    $stmt = db()->prepare($sql);

    return $stmt->execute([$title, $description, $categoryId, $id]);
}

function content_delete($id)
{
    $content = content_find($id);

    if ($content && !empty($content['file_path'])) {
        $filePath = PUBLIC_PATH . '/' . $content['file_path'];

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $sql = "DELETE FROM contents WHERE id = ?";
    $stmt = db()->prepare($sql);

    return $stmt->execute([$id]);
}

function content_increment_download($id)
{
    $sql = "UPDATE contents SET download_count = download_count + 1 WHERE id = ?";
    $stmt = db()->prepare($sql);

    return $stmt->execute([$id]);
}

function content_count_all()
{
    $sql = "SELECT COUNT(*) AS total FROM contents";
    $stmt = db()->query($sql);
    $row = $stmt->fetch();

    return $row['total'];
}
