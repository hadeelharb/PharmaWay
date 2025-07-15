<?php
$host = 'localhost';
$db = 'sr';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("فشل الاتصال: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['audio']) && isset($_POST['transcript'])) {
        $audioName = uniqid() . '.webm';
        $audioPath = 'uploads/' . $audioName;

        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        move_uploaded_file($_FILES['audio']['tmp_name'], $audioPath);

        $stmt = $pdo->prepare("INSERT INTO voice_entries (audio_path, transcript) VALUES (?, ?)");
        $stmt->execute([$audioPath, $_POST['transcript']]);

        echo "تم الحفظ بنجاح!";
    } else {
        echo "البيانات غير كاملة.";
    }
}
?>
