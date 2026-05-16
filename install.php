<?php
require_once __DIR__ . '/db.php';

function getInstallerPDO(string $host, string $user, string $pass): ?PDO
{
    $dsn = sprintf('mysql:host=%s;charset=%s', $host, DB_CHARSET);

    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        return null;
    }
}

function writeDbConfig(string $host, string $name, string $user, string $pass): bool
{
    $config = <<<PHP
<?php
const DB_HOST = '{$host}';
const DB_NAME = '{$name}';
const DB_USER = '{$user}';
const DB_PASS = '{$pass}';
PHP;

    return file_put_contents(__DIR__ . '/db-config.php', $config) !== false;
}

function executeSqlFile(PDO $pdo, string $sqlFile)
{
    if (!file_exists($sqlFile)) {
        throw new RuntimeException('ملف SQL غير موجود: ' . $sqlFile);
    }

    $lines = file($sqlFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $sql = '';
    foreach ($lines as $line) {
        $trim = trim($line);
        if ($trim === '' || str_starts_with($trim, '--') || str_starts_with($trim, '#')) {
            continue;
        }

        $sql .= $line . "\n";
    }

    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $statement) {
        if ($statement === '') {
            continue;
        }
        $pdo->exec($statement);
    }
}

function isAlreadyInstalled(PDO $pdo): bool
{
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
        return (bool) $stmt->fetchColumn();
    } catch (PDOException $e) {
        return false;
    }
}

$installed = file_exists(__DIR__ . '/install.lock');
$message = '';
$error = '';
$dbHost = '127.0.0.1';
$dbName = 'alasuma';
$dbUser = 'root';
$dbPass = '';
$adminUsername = 'admin';
$adminPassword = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$installed) {
    $dbHost = trim($_POST['db_host'] ?? $dbHost);
    $dbName = trim($_POST['db_name'] ?? $dbName);
    $dbUser = trim($_POST['db_user'] ?? $dbUser);
    $dbPass = trim($_POST['db_pass'] ?? $dbPass);
    $adminUsername = trim($_POST['admin_username'] ?? $adminUsername);
    $adminPassword = trim($_POST['admin_password'] ?? $adminPassword);

    if (!$dbHost || !$dbName || !$dbUser || !$adminUsername || !$adminPassword) {
        $error = 'جميع الحقول مطلوبة لإكمال التثبيت.';
    } else {
        $installerPdo = getInstallerPDO($dbHost, $dbUser, $dbPass);
        if (!$installerPdo) {
            $error = 'فشل الاتصال بخادم MySQL. تحقق من بيانات الاتصال.';
        } else {
            try {
                $escapedDbName = str_replace('`', '``', $dbName);
                $installerPdo->exec("CREATE DATABASE IF NOT EXISTS `{$escapedDbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                if (!writeDbConfig($dbHost, $dbName, $dbUser, $dbPass)) {
                    throw new RuntimeException('فشل كتابة ملف إعدادات قاعدة البيانات db-config.php. تحقق من أذونات الكتابة.');
                }

                executeSqlFile($installerPdo, __DIR__ . '/schema.sql');

                $pdo = getPDO();
                if (!isAlreadyInstalled($pdo)) {
                    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('INSERT INTO admins (username, password) VALUES (?, ?)');
                    $stmt->execute([$adminUsername, $hashedPassword]);
                }

                file_put_contents(__DIR__ . '/install.lock', date('Y-m-d H:i:s'));
                $installed = true;
                $message = 'تم تثبيت الموقع وقاعدة البيانات بنجاح. يمكنك الآن استخدام index.php و admin.php.';
            } catch (Exception $e) {
                $error = 'حدث خطأ أثناء التثبيت: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت موقع فحم العاصمة</title>
    <style>
        body { font-family: Tahoma, sans-serif; background: #111; color: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .install-box { width: 100%; max-width: 520px; background: #1f1f1f; border: 1px solid rgba(201,168,76,0.3); border-radius: 18px; padding: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        h1 { margin-bottom: 15px; color: #c9a84c; }
        p { color: #ccc; line-height: 1.8; }
        label { display: block; margin-bottom: 8px; font-size: 14px; color: #ddd; }
        input { width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid #333; background: #121212; color: #fff; margin-bottom: 18px; }
        button { width: 100%; padding: 14px; border: none; border-radius: 12px; background: #c9a84c; color: #111; font-size: 16px; cursor: pointer; }
        .message { margin: 18px 0; padding: 14px 18px; border-radius: 12px; }
        .success { background: rgba(40,167,69,0.15); color: #9fe0a0; }
        .error { background: rgba(220,53,69,0.15); color: #ff9fa3; }
        .links { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 20px; }
        .links a { color: #c9a84c; text-decoration: none; }
    </style>
</head>
<body>
    <div class="install-box">
        <h1>تثبيت فحم العاصمة</h1>
        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
            <div class="links">
                <a href="index.php">اذهب إلى الموقع</a>
                <a href="admin.php">اذهب إلى لوحة التحكم</a>
            </div>
        <?php elseif ($installed): ?>
            <div class="message success">تم التثبيت سابقاً. يمكنك الآن استخدام الموقع.</div>
            <div class="links">
                <a href="index.php">الموقع العام</a>
                <a href="admin.php">لوحة التحكم</a>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <div class="message error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <p>أدخل بيانات اتصال قاعدة البيانات ثم اضغط تثبيت. سيتم إنشاء القاعدة والجداول وحساب الأدمن تلقائياً.</p>
            <form method="post">
                <label>خادم MySQL</label>
                <input type="text" name="db_host" value="<?= htmlspecialchars($dbHost) ?>" required>
                <label>اسم قاعدة البيانات</label>
                <input type="text" name="db_name" value="<?= htmlspecialchars($dbName) ?>" required>
                <label>اسم مستخدم قاعدة البيانات</label>
                <input type="text" name="db_user" value="<?= htmlspecialchars($dbUser) ?>" required>
                <label>كلمة مرور قاعدة البيانات</label>
                <input type="text" name="db_pass" value="<?= htmlspecialchars($dbPass) ?>">
                <label>اسم مستخدم الأدمن</label>
                <input type="text" name="admin_username" value="<?= htmlspecialchars($adminUsername) ?>" required>
                <label>كلمة مرور الأدمن</label>
                <input type="text" name="admin_password" value="<?= htmlspecialchars($adminPassword) ?>" required>
                <button type="submit">تثبيت الموقع</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
