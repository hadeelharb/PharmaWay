<?php
$conn = new mysqli("localhost", "root", "", "sr");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

if (isset($_POST['news_id']) && isset($_POST['action'])) {
    $news_id = $_POST['news_id'];
    $action = $_POST['action'];

    if ($action == 'activate') {
        $stmt = $conn->prepare("UPDATE news SET status = 'active' WHERE id = ?");
    } elseif ($action == 'deactivate') {
        $stmt = $conn->prepare("UPDATE news SET status = 'inactive' WHERE id = ?");
    }

    $stmt->bind_param("i", $news_id);
    if ($stmt->execute()) {
        header('Location: news.php'); // استبدل `your_news_page.php` باسم الصفحة التي تحتوي على نموذج الأخبار
        exit;
    } else {
        echo "حدث خطأ أثناء تحديث الحالة.";
    }

    $stmt->close();
}

$conn->close();
?>