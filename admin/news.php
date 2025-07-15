<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>شريط الأخبار العاجلة</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      direction: rtl;
      background-color: #f9f9f9;
      margin: 20px;
    }

    form {
      background: #fff;
      padding: 20px;
      box-shadow: 0 0 10px #ccc;
      margin-bottom: 30px;
    }

    label {
      font-weight: bold;
    }

    textarea, select, button {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
    }

    button {
      background-color: #d32f2f;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #b71c1c;
    }

    .ticker {
      display: flex;
      background-color: #e53935;
      color: white;
      padding: 10px;
      align-items: center;
      overflow: hidden;
    }

    .ticker .title {
      background-color: #b71c1c;
      padding: 10px;
      font-weight: bold;
    }

    .ticker .news {
      overflow: hidden;
      white-space: nowrap;
      margin-right: 20px;
      flex: 1;
    }

    .ticker .news-content {
      display: inline-block;
      animation: scroll 15s linear infinite;
    }

    .ticker .news-content p {
      display: inline;
      margin: 0 50px;
    }

    @keyframes scroll {
      0% { transform: translateX(100%); }
      100% { transform: translateX(-100%); }
    }

    .news-list {
      margin-top: 20px;
    }

    .news-item {
      background: #fff;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 5px;
      box-shadow: 0 0 5px #ccc;
    }

    .news-item button {
      background-color: #4caf50; /* لون زر التفعيل */
    }

    .news-item button.inactive {
      background-color: #f44336; /* لون زر الإلغاء */
    }
  </style>
</head>
<body>

<?php
// معالجة الإدخال
if (isset($_POST['submit'])) {
  $content = $_POST['content'];
  $status = $_POST['status'];

  $conn = new mysqli("localhost", "root", "", "sr");
  $conn->set_charset("utf8");

  if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("INSERT INTO news (content, status) VALUES (?, ?)");
  $stmt->bind_param("ss", $content, $status);

  if ($stmt->execute()) {
    echo "<p style='color: green;'>✅ تم إدخال الخبر بنجاح!</p>";
  } else {
    echo "<p style='color: red;'>❌ حدث خطأ أثناء الإدخال!</p>";
  }

  $stmt->close();
  $conn->close();
}
?>

<!-- نموذج إدخال الأخبار -->
<form method="POST" action="">
  <h3>إضافة خبر عاجل</h3>
  <label for="content">نص الخبر:</label>
  <textarea name="content" id="content" rows="3" required></textarea>

  <label for="status">الحالة:</label>
  <select name="status" id="status" required>
    <option value="active">مفعل</option>
    <option value="inactive">غير مفعل</option>
  </select>

  <button type="submit" name="submit">إضافة الخبر</button>
</form>

<!-- شريط الأخبار -->
<div class="ticker">
  <div class="title"><h5>عاجل</h5></div>
  <div class="news">
    <div class="news-content">
      <?php
      $conn = new mysqli("localhost", "root", "", "sr");
      $conn->set_charset("utf8");

      if ($conn->connect_error) {
        die("فشل الاتصال: " . $conn->connect_error);
      }

      $sql = "SELECT content FROM news WHERE status = 'active' ORDER BY id DESC LIMIT 10";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<p>" . htmlspecialchars($row['content']) . "</p>";
        }
      } else {
        echo "<p>لا توجد أخبار حالياً.</p>";
      }

      $conn->close();
      ?>
    </div>
  </div>
</div>

<!-- عرض جميع الأخبار مع إمكانية التفعيل والإلغاء -->
<div class="news-list">
  <h3>جميع الأخبار</h3>
  <?php
  $conn = new mysqli("localhost", "root", "", "sr");
  $conn->set_charset("utf8");

  if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM news ORDER BY id DESC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '<div class="news-item">';
      echo '<p><strong>نص الخبر:</strong> ' . htmlspecialchars($row['content']) . '</p>';
      echo '<p><strong>الحالة:</strong> ' . htmlspecialchars($row['status']) . '</p>';
      echo '<form method="POST" action="toggle_status.php">';
      echo '<input type="hidden" name="news_id" value="' . $row['id'] . '">';
      if ($row['status'] == 'active') {
        echo '<button type="submit" name="action" value="deactivate" class="inactive">إلغاء تفعيل</button>';
      } else {
        echo '<button type="submit" name="action" value="activate">تفعيل</button>';
      }
      echo '</form>';
      echo '</div>';
    }
  } else {
    echo "<p>لا توجد أخبار مسجلة.</p>";
  }

  $conn->close();
  ?>
</div>

</body>
</html>