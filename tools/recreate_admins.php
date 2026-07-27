<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=db_payroll_nexatech;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('DROP TABLE IF EXISTS admins');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$pdo->exec("CREATE TABLE admins (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'Admin',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY admins_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$hash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
$pdo->exec("INSERT INTO admins (name, email, password, role, created_at, updated_at) VALUES (
    'System Administrator',
    'admin@nexatech.ph',
    '$hash',
    'Admin',
    NOW(),
    NOW()
)");

$stmt = $pdo->query("SELECT id, email, role FROM admins WHERE email = 'admin@nexatech.ph'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
