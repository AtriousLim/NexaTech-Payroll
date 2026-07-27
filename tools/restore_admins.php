<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=db_payroll_nexatech;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec('CREATE TABLE IF NOT EXISTS admins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL DEFAULT "Admin",
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)');

$pdo->exec('INSERT INTO admins (name, email, password, role, created_at, updated_at)
    SELECT "System Administrator", "admin@nexatech.ph", "$2y$12$4wuOWKrnFVHXYYdiS2O81.G50Mn075VAQwAmDDv5k3VqDa7QLWfZS", "Admin", NOW(), NOW()
    WHERE NOT EXISTS (SELECT 1 FROM admins WHERE email = "admin@nexatech.ph")');

$stmt = $pdo->query("SHOW TABLES FROM db_payroll_nexatech LIKE 'admins'");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
var_export($tables);
echo PHP_EOL;

$stmt = $pdo->query('SELECT id, email, role FROM admins WHERE email = "admin@nexatech.ph"');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
