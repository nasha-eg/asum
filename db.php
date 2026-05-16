<?php
// db.php - قاعدة بيانات MySQL عبر PDO

if (file_exists(__DIR__ . '/db-config.php')) {
    require_once __DIR__ . '/db-config.php';
}

if (!defined('DB_HOST')) {
    const DB_HOST = '127.0.0.1';
    const DB_NAME = 'alasuma';
    const DB_USER = 'root';
    const DB_PASS = '';
}

const DB_CHARSET = 'utf8mb4';

function getPDO()
{
    static $pdo;
    if ($pdo) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات: ' . $e->getMessage()]);
        exit;
    }

    return $pdo;
}
