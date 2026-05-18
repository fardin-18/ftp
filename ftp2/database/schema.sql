CREATE DATABASE IF NOT EXISTS ftp_media_mvc;
USE ftp_media_mvc;

DROP TABLE IF EXISTS content_requests;
DROP TABLE IF EXISTS contents;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','moderator') NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE contents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    uploader_id INT NOT NULL,
    download_count INT DEFAULT 0,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (uploader_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE content_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_ip VARCHAR(80),
    content_title VARCHAR(150) NOT NULL,
    category_requested VARCHAR(100),
    message TEXT,
    status ENUM('pending','fulfilled','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Password for both demo accounts: Password123
INSERT INTO users (name, email, password_hash, role) VALUES
('Demo Admin', 'admin@isp.test', '$2y$12$140rKtGwVfT8EtqI5ubVueWgifzvJfqwhI8CT0DldyToIQZgFgQb.', 'admin'),
('Demo Moderator', 'moderator@isp.test', '$2y$12$140rKtGwVfT8EtqI5ubVueWgifzvJfqwhI8CT0DldyToIQZgFgQb.', 'moderator');

INSERT INTO categories (name, parent_id) VALUES
('Movies', NULL),
('Software', NULL),
('TV Series', NULL),
('Games', NULL),
('English Movies', 1),
('Action Movies', 1),
('Comedy Movies', 1),
('Utility Software', 2),
('Antivirus', 2),
('English TV Series', 3),
('PC Games', 4),
('Mobile Games', 4);
