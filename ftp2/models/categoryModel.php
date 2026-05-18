<?php

function category_all()
{
    $sql = "SELECT * FROM categories ORDER BY parent_id ASC, name ASC";
    $stmt = db()->query($sql);

    return $stmt->fetchAll();
}

function category_top_level()
{
    $sql = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC";
    $stmt = db()->query($sql);

    return $stmt->fetchAll();
}

function category_children($parentId)
{
    $sql = "SELECT * FROM categories WHERE parent_id = ? ORDER BY name ASC";
    $stmt = db()->prepare($sql);
    $stmt->execute([$parentId]);

    return $stmt->fetchAll();
}

function category_find($id)
{
    $sql = "SELECT * FROM categories WHERE id = ? LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}

function category_count_all()
{
    $sql = "SELECT COUNT(*) AS total FROM categories";
    $stmt = db()->query($sql);
    $row = $stmt->fetch();

    return $row['total'];
}

function category_ids_with_children($categoryId)
{
    $allCategories = category_all();
    $ids = [(int)$categoryId];
    $foundMore = true;

    while ($foundMore) {
        $foundMore = false;

        foreach ($allCategories as $category) {
            $parentId = (int)$category['parent_id'];
            $id = (int)$category['id'];

            if ($parentId > 0 && in_array($parentId, $ids) && !in_array($id, $ids)) {
                $ids[] = $id;
                $foundMore = true;
            }
        }
    }

    return $ids;
}
