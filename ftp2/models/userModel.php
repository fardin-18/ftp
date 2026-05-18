<?php

function user_create($name, $email, $password, $role)
{
    $sql = "INSERT INTO users(name, email, password_hash, role) VALUES(?, ?, ?, ?)";
    $stmt = db()->prepare($sql);

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    return $stmt->execute([$name, $email, $passwordHash, $role]);
}

function user_email_exists($email, $ignoreId = null)
{
    if ($ignoreId == null) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = db()->prepare($sql);
        $stmt->execute([$email]);
    } else {
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = db()->prepare($sql);
        $stmt->execute([$email, $ignoreId]);
    }

    return $stmt->fetch() ? true : false;
}

function user_find_by_email($email)
{
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute([$email]);

    return $stmt->fetch();
}

function user_find($id)
{
    $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch();
}

function user_update_profile($id, $name, $email, $profilePicture = null)
{
    if ($profilePicture != null) {
        $sql = "UPDATE users SET name = ?, email = ?, profile_picture = ? WHERE id = ?";
        $stmt = db()->prepare($sql);
        return $stmt->execute([$name, $email, $profilePicture, $id]);
    }

    $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = db()->prepare($sql);

    return $stmt->execute([$name, $email, $id]);
}

function user_update_password($id, $password)
{
    $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
    $stmt = db()->prepare($sql);

    return $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
}

function user_all_moderators()
{
    $sql = "SELECT * FROM users WHERE role = 'moderator' ORDER BY created_at DESC";
    $stmt = db()->query($sql);

    return $stmt->fetchAll();
}

function user_delete_moderator($id)
{
    $sql = "DELETE FROM users WHERE id = ? AND role = 'moderator'";
    $stmt = db()->prepare($sql);

    return $stmt->execute([$id]);
}

function user_count_moderators()
{
    $sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'moderator'";
    $stmt = db()->query($sql);
    $row = $stmt->fetch();

    return $row['total'];
}
