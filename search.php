<?php
// الاتصال بقاعدة البيانات
$conn = new PDO("mysql:host=localhost;dbname=sr", "root", "");

// استلام استعلام البحث
$query = isset($_POST['query']) ? trim($_POST['query']) : '';

// التحقق من وجود استعلام بحث
if ($query !== '') {
    $searchQuery = "%" . $query . "%";

    // الاستعلام عن المنتجات باستخدام LIKE
    $stmt = $conn->prepare("SELECT * FROM products WHERE title LIKE ? LIMIT 10");
    $stmt->execute([$searchQuery]);

    // إذا كانت هناك نتائج
    if ($stmt->rowCount() > 0) {
        echo "<ul class='list-group'>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id']; // افترض أن عمود الـ ID يسمى 'id' في قاعدة البيانات
            $title = htmlspecialchars($row['title']);
            echo "<li class='list-group-item'>
                    <a href='quick_view.php?pid=$id' class='text-decoration-none'>$title</a>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='text-danger'>لا توجد نتائج مطابقة</p>";
    }
} else {
    echo "<p class='text-muted'>الرجاء إدخال كلمة للبحث</p>";
}
?>
