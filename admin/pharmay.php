<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "sr"); // عدّل اسم القاعدة
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// إدخال صيدلية جديدة إذا تم إرسال النموذج
if (isset($_POST['submit'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $insert = "INSERT INTO pharmacies (name, address, phone) VALUES ('$name', '$address', '$phone')";
    $conn->query($insert);
}

// جلب الصيدليات خلال آخر 24 ساعة
$sql = "SELECT * FROM pharmacies WHERE created_at >= NOW() - INTERVAL 1 DAY ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الصيدليات المناوبة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

<div class="container py-5">
  <h2 class="text-center text-success mb-4">إضافة صيدلية مناوبة</h2>

  <!-- نموذج الإدخال -->
  <form method="POST" class="card p-4 mb-5 shadow-sm bg-white">
    <div class="mb-3">
      <label class="form-label">اسم الصيدلية</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">العنوان</label>
      <textarea name="address" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">رقم الهاتف (اختياري)</label>
      <input type="text" name="phone" class="form-control">
    </div>
    <button type="submit" name="submit" class="btn btn-success w-100">إضافة</button>
  </form>

  <!-- عرض الصيدليات المناوبة -->
  <h3 class="text-center text-primary mb-4">الصيدليات المناوبة خلال 24 ساعة</h3>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="row">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
              <p class="card-text"><?= nl2br(htmlspecialchars($row['address'])) ?></p>
              <?php if (!empty($row['phone'])): ?>
                <p class="card-text"><strong>📞 الهاتف:</strong> <?= htmlspecialchars($row['phone']) ?></p>
              <?php endif; ?>
              <small class="text-muted">🕒 أضيفت: <?= $row['created_at'] ?></small>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">لا توجد صيدليات مناوبة حالياً.</div>
  <?php endif; ?>

</div>
</body>
</html>
