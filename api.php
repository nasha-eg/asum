<?php
require_once __DIR__ . '/db.php';
session_start();
header('Content-Type: application/json; charset=UTF-8');

$action = $_REQUEST['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

function requestParam($name)
{
    global $input;
    return $input[$name] ?? $_REQUEST[$name] ?? null;
}

switch ($action) {
    case 'login':
        login($input);
        break;
    case 'logout':
        logout();
        break;
    case 'get_articles':
        getArticles();
        break;
    case 'get_article':
        getArticle();
        break;
    case 'save_article':
        saveArticle($input);
        break;
    case 'delete_article':
        deleteArticle();
        break;
    case 'get_gallery':
        getGallery();
        break;
    case 'save_gallery':
        saveGallery($input);
        break;
    case 'delete_gallery':
        deleteGallery();
        break;
    case 'get_products':
        getProducts();
        break;
    case 'save_product':
        saveProduct($input);
        break;
    case 'delete_product':
        deleteProduct();
        break;
    case 'get_settings':
        getSettings();
        break;
    case 'save_settings':
        saveSettings($input);
        break;
    case 'dashboard':
        getDashboard();
        break;
    default:
        respond(['success' => false, 'message' => 'Action غير مدعوم']);
}

function respond($data)
{
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function authRequired()
{
    if (empty($_SESSION['admin_id'])) {
        respond(['success' => false, 'message' => 'غير مصرح بالوصول', 'auth' => false]);
    }
}

function login($input)
{
    $username = trim($input['username'] ?? '');
    $password = trim($input['password'] ?? '');

    if (!$username || !$password) {
        respond(['success' => false, 'message' => 'ادخل اسم المستخدم وكلمة المرور']);
    }

    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, username, password FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password'])) {
        respond(['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيح']);
    }

    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];

    respond(['success' => true, 'message' => 'تم تسجيل الدخول بنجاح']);
}

function logout()
{
    session_unset();
    session_destroy();
    respond(['success' => true, 'message' => 'تم تسجيل الخروج']);
}

function getArticles()
{
    $pdo = getPDO();
    $stmt = $pdo->query('SELECT id, title, description, image, created_at FROM articles ORDER BY created_at DESC');
    $articles = $stmt->fetchAll();
    respond(['success' => true, 'articles' => $articles]);
}

function getArticle()
{
    $id = intval($_REQUEST['id'] ?? 0);
    if (!$id) {
        respond(['success' => false, 'message' => 'رقم المقال غير صالح']);
    }
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, title, description, content, image, created_at FROM articles WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $article = $stmt->fetch();
    if (!$article) {
        respond(['success' => false, 'message' => 'المقال غير موجود']);
    }
    respond(['success' => true, 'article' => $article]);
}

function saveArticle($input)
{
    authRequired();
    $id = intval($input['id'] ?? 0);
    $title = trim($input['title'] ?? '');
    $content = trim($input['content'] ?? '');
    $description = trim($input['description'] ?? '');
    $imageUrl = trim($input['image_url'] ?? '');
    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imagePath = uploadImage($_FILES['image']);
    }

    if (!$imagePath) {
        $imagePath = $imageUrl;
    }

    if (!$title || !$content) {
        respond(['success' => false, 'message' => 'الرجاء إدخال العنوان والمحتوى']);
    }

    if (!$description) {
        $description = mb_substr(strip_tags($content), 0, 160) . '...';
    }

    $pdo = getPDO();

    if ($id) {
        $stmt = $pdo->prepare('SELECT image FROM articles WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $existing = $stmt->fetch();
        if (!$imagePath) {
            $imagePath = $existing['image'] ?? '';
        }

        $stmt = $pdo->prepare('UPDATE articles SET title = ?, description = ?, content = ?, image = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$title, $description, $content, $imagePath, $id]);
        respond(['success' => true, 'message' => 'تم تحديث المقال بنجاح', 'id' => $id]);
    }

    $stmt = $pdo->prepare('INSERT INTO articles (title, description, content, image) VALUES (?, ?, ?, ?)');
    $stmt->execute([$title, $description, $content, $imagePath]);
    respond(['success' => true, 'message' => 'تم إنشاء المقال بنجاح', 'id' => $pdo->lastInsertId()]);
}

function deleteArticle()
{
    authRequired();
    $id = intval($_REQUEST['id'] ?? 0);
    if (!$id) {
        respond(['success' => false, 'message' => 'رقم المقال غير صالح']);
    }
    $pdo = getPDO();
    $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
    $stmt->execute([$id]);
    respond(['success' => true, 'message' => 'تم حذف المقال']);
}

function getGallery()
{
    $pdo = getPDO();
    $stmt = $pdo->query('SELECT id, title, image, created_at FROM gallery_images ORDER BY created_at DESC');
    respond(['success' => true, 'gallery' => $stmt->fetchAll()]);
}

function saveGallery($input)
{
    authRequired();
    $id = intval($input['id'] ?? 0);
    $title = trim($input['title'] ?? '');
    $imageUrl = trim($input['image_url'] ?? '');
    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imagePath = uploadImage($_FILES['image']);
    }

    if (!$title || (!$imageUrl && !$imagePath)) {
        respond(['success' => false, 'message' => 'الرجاء إدخال عنوان وصورة للمعرض']);
    }

    if (!$imagePath) {
        $imagePath = $imageUrl;
    }

    $pdo = getPDO();

    if ($id) {
        $stmt = $pdo->prepare('UPDATE gallery_images SET title = ?, image = ? WHERE id = ?');
        $stmt->execute([$title, $imagePath, $id]);
        respond(['success' => true, 'message' => 'تم تحديث صورة المعرض', 'id' => $id]);
    }

    $stmt = $pdo->prepare('INSERT INTO gallery_images (title, image) VALUES (?, ?)');
    $stmt->execute([$title, $imagePath]);
    respond(['success' => true, 'message' => 'تم إضافة صورة للمعرض', 'id' => $pdo->lastInsertId()]);
}

function deleteGallery()
{
    authRequired();
    $id = intval($_REQUEST['id'] ?? 0);
    if (!$id) {
        respond(['success' => false, 'message' => 'رقم الصورة غير صالح']);
    }
    $pdo = getPDO();
    $stmt = $pdo->prepare('DELETE FROM gallery_images WHERE id = ?');
    $stmt->execute([$id]);
    respond(['success' => true, 'message' => 'تم حذف صورة المعرض']);
}

function getProducts()
{
    $pdo = getPDO();
    $stmt = $pdo->query('SELECT id, name, type, price, stock, status, description, image FROM products ORDER BY id DESC');
    respond(['success' => true, 'products' => $stmt->fetchAll()]);
}

function saveProduct($input)
{
    authRequired();
    $id = intval($input['id'] ?? 0);
    $name = trim($input['name'] ?? '');
    $type = trim($input['type'] ?? '');
    $price = floatval($input['price'] ?? 0);
    $stock = intval($input['stock'] ?? 0);
    $description = trim($input['description'] ?? '');
    $status = trim($input['status'] ?? 'active');
    $image = trim($input['image'] ?? '');

    if (!$name || !$type || !$price) {
        respond(['success' => false, 'message' => 'الرجاء إدخال اسم المنتج، نوعه، وسعره']);
    }

    $pdo = getPDO();

    if ($id) {
        $stmt = $pdo->prepare('UPDATE products SET name = ?, type = ?, price = ?, stock = ?, status = ?, description = ?, image = ? WHERE id = ?');
        $stmt->execute([$name, $type, $price, $stock, $status, $description, $image, $id]);
        respond(['success' => true, 'message' => 'تم تحديث المنتج', 'id' => $id]);
    }

    $stmt = $pdo->prepare('INSERT INTO products (name, type, price, stock, status, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $type, $price, $stock, $status, $description, $image]);
    respond(['success' => true, 'message' => 'تم إضافة المنتج', 'id' => $pdo->lastInsertId()]);
}

function deleteProduct()
{
    authRequired();
    $id = intval($_REQUEST['id'] ?? 0);
    if (!$id) {
        respond(['success' => false, 'message' => 'رقم المنتج غير صالح']);
    }
    $pdo = getPDO();
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
    respond(['success' => true, 'message' => 'تم حذف المنتج']);
}

function getDashboard()
{
    authRequired();
    $pdo = getPDO();
    $totalProducts = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $totalArticles = $pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn();
    $totalGallery = $pdo->query('SELECT COUNT(*) FROM gallery_images')->fetchColumn();
    respond(['success' => true, 'stats' => [
        'products' => intval($totalProducts),
        'articles' => intval($totalArticles),
        'gallery' => intval($totalGallery),
    ]]);
}

function ensureSettingsTable(PDO $pdo)
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS `site_settings` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `setting_key` VARCHAR(100) NOT NULL,
            `setting_value` TEXT,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `idx_setting_key` (`setting_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;'
    );
}

function getSettings()
{
    $pdo = getPDO();
    ensureSettingsTable($pdo);
    $stmt = $pdo->query('SELECT setting_key, setting_value FROM site_settings');
    $settings = [];
    foreach ($stmt->fetchAll() as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    respond(['success' => true, 'settings' => $settings]);
}

function saveSettings($input)
{
    authRequired();
    $settings = $input['settings'] ?? [];
    if (!is_array($settings)) {
        respond(['success' => false, 'message' => 'تنسيق الإعدادات غير صالح']);
    }

    $pdo = getPDO();
    ensureSettingsTable($pdo);
    $stmt = $pdo->prepare('INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    foreach ($settings as $key => $value) {
        $stmt->execute([$key, trim((string) $value)]);
    }

    respond(['success' => true, 'message' => 'تم حفظ إعدادات الموقع بنجاح']);
}

function uploadImage($file)
{
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed, true)) {
        respond(['success' => false, 'message' => 'الملف غير مدعوم، استخدم صورة PNG أو JPG أو WEBP']);
    }

    if (!is_dir(__DIR__ . '/uploads')) {
        mkdir(__DIR__ . '/uploads', 0755, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_', true) . '.' . $ext;
    $target = __DIR__ . '/uploads/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        respond(['success' => false, 'message' => 'فشل رفع الصورة']);
    }

    return 'uploads/' . $filename;
}
