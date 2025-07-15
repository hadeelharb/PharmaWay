<?php
// إعدادات اتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sr";

// إنشاء اتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استعلام جلب جميع التسجيلات الصوتية المرتبة حسب تاريخ الإنشاء تنازلياً
$sql = "SELECT id, audio_path, transcript, created_at FROM voice_entries ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>إدارة التسجيلات الصوتية</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        direction: rtl;
        text-align: right;
        background: #f7f9fc;
        margin: 0; padding: 20px;
        color: #333;
    }
    header {
        background-color: #2980b9;
        color: white;
        font-size: 24px;
        font-weight: bold;
        padding: 15px 20px;
        text-align: center;
        margin-bottom: 20px;
    }
    .container {
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        padding: 20px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h1 {
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px 12px;
        vertical-align: middle;
    }
    th {
        background-color: #2980b9;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f9fafd;
    }
    audio {
        width: 180px;
        outline: none;
    }
    .transcript {
        white-space: pre-wrap; /* يحافظ على التنسيق */
        max-height: 100px;
        overflow-y: auto;
    }
    .created_at {
        white-space: nowrap;
        color: #666;
    }
    .actions {
        text-align: center;
    }
    .btn-delete {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 6px 12px;
        cursor: pointer;
        border-radius: 4px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    .btn-delete:hover {
        background-color: #c0392b;
    }
    a.button-home {
        display: inline-block;
        margin-bottom: 15px;
        padding: 10px 20px;
        background-color: #2980b9;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    a.button-home:hover {
        background-color: #1c5980;
    }
</style>
<script>
function confirmDelete(id) {
    if (confirm('هل أنت متأكد من حذف هذا التسجيل؟')) {
        window.location.href = '?delete_id=' + id;
    }
}
</script>
</head>
<body>
<header>Pharma way - إدارة التسجيلات الصوتية</header>
<div class="container">

<a href="index.php" class="button-home">العودة للصفحة الرئيسية</a>

<h1>قائمة التسجيلات الصوتية</h1>

<?php
// معالجة حذف تسجيل إذا تم الطلب
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    $del_stmt = $conn->prepare("DELETE FROM voice_entries WHERE id = ?");
    $del_stmt->bind_param("i", $del_id);
    if ($del_stmt->execute()) {
        echo "<div style='padding:12px;background:#dff0d8;color:#3c763d;border-radius:5px;margin-bottom:15px;text-align:center;'>تم حذف التسجيل بنجاح.</div>";
        // إعادة تحميل الصفحة لتحديث القائمة
        echo "<script>setTimeout(() => { window.location.href = window.location.pathname; }, 1500);</script>";
    } else {
        echo "<div style='padding:12px;background:#f2dede;color:#a94442;border-radius:5px;margin-bottom:15px;text-align:center;'>حدث خطأ أثناء حذف التسجيل.</div>";
    }
    $del_stmt->close();
}

// استعلام جديد للعرض بعد الحذف
$result = $conn->query($sql);
?>

<?php if ($result && $result->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>رقم التسجيل</th>
            <th>التسجيل الصوتي</th>
            <th>النص الكامل</th>
            <th>تاريخ الإضافة</th>
            <th>العمليات</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td>
                <?php if (!empty($row['audio_path']) && file_exists($row['audio_path'])): ?>
                    <audio controls preload="none" >
                        <source src="../uploads/<?php echo htmlspecialchars($row['audio_path']); ?>" type="audio/mpeg">
                        متصفحك لا يدعم تشغيل الصوت.
                    </audio>
                <?php else: ?>
                    لا يوجد ملف صوتي
                <?php endif; ?>
            </td>
            <td class="transcript"><?php echo nl2br(htmlspecialchars($row['transcript'])); ?></td>
            <td class="created_at"><?php echo htmlspecialchars($row['created_at']); ?></td>
            <td class="actions">
                <button class="btn-delete" type="button" onclick="confirmDelete(<?php echo $row['id']; ?>)">حذف</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php else: ?>
    <p style="text-align: center; color: #777;">لا توجد تسجيلات صوتية حالياً.</p>
<?php endif; ?>

</div>
</body>
</html>
<?php $conn->close(); ?>
