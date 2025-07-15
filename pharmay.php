
<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "sr");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
$now = date("Y-m-d H:i:s");
$sql = "SELECT * FROM pharmacies WHERE created_at >= NOW() - INTERVAL 1 DAY ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الصيدليات المناوبة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Cairo', sans-serif;
    }

    .section-title {
      background-color: #198754;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      margin-bottom: 25px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .card {
      border: none;
      border-radius: 12px;
      transition: transform 0.3s ease-in-out;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      background-color: #ffffff;
      border-radius: 12px;
      padding: 20px;
    }

    .card-title {
      font-size: 1.3rem;
      margin-bottom: 10px;
    }

    .card-text {
      color: #555;
      margin-bottom: 8px;
    }

    .text-muted {
      font-size: 0.85rem;
    }
  </style>
</head>
<body class="container py-4">

<?php if ($result->num_rows > 0): ?>
  <div class="section-title text-center">
    <h4><i class="bi bi-capsule-pill me-2"></i> الصيدليات المناوبة خلال 24 ساعة</h4>
  </div>

  <div class="row">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title text-success">
              <i class="bi bi-shop-window me-1"></i><?= htmlspecialchars($row['name']) ?>
            </h5>
            <p class="card-text">
              <i class="bi bi-geo-alt-fill me-1 text-danger"></i><?= htmlspecialchars($row['address']) ?>
            </p>
            <?php if (!empty($row['phone'])): ?>
              <p class="card-text">
                <i class="bi bi-telephone-fill me-1 text-primary"></i><strong>الهاتف:</strong> <?= htmlspecialchars($row['phone']) ?>
              </p>
            <?php endif; ?>
            <p class="text-muted">
              <i class="bi bi-clock-history me-1"></i>أضيفت بتاريخ: <?= date("Y-m-d H:i", strtotime($row['created_at'])) ?>
            </p>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

<?php else: ?>
  <div class="alert alert-info text-center mt-4">
    <i class="bi bi-info-circle-fill me-2"></i>لا توجد صيدليات مناوبة حاليًا.
  </div>
<?php endif; ?>

</body>
</html>
