<?php
$pdo = new PDO("mysql:host=localhost;dbname=sr;charset=utf8", 'root', '');
$entries = $pdo->query("SELECT * FROM voice_entries ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عرض التسجيلات</title>
    <style>
        body { font-family: Arial; padding: 20px; direction: rtl; }
        .entry { border-bottom: 1px solid #ccc; padding: 15px; }
        audio { width: 300px; }
    </style>
</head>
<body>
    <h2>تسجيلات المستخدمين</h2>
    <?php foreach ($entries as $entry): ?>
        <div class="entry">
            <p><strong>النص:</strong> <?= htmlspecialchars($entry['transcript']) ?></p>
            <audio controls src="<?= htmlspecialchars($entry['audio_path']) ?>"></audio>
            <p><em>التاريخ: <?= $entry['created_at'] ?></em></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
