<?php
// الاتصال بقاعدة البيانات
$host = "localhost";
$user = "root";
$password = "";
$database = "sr";

$conn = new mysqli($host, $user, $password, $database);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// جلب جميع المقالات من قاعدة البيانات
$sql = "SELECT * FROM posts ORDER BY date DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جميع المقالات</title>
    <link rel="stylesheet" href="./css/style.css"> <!-- استبدل بمسار ملف CSS الخاص بك -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header, footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 15px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: calc(33.333% - 20px);
            overflow: hidden;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px;
        }
        .card-description {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
            height: 50px;
            overflow: hidden;
        }
        .card a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .card a:hover {
            text-decoration: underline;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1> Pharma way</h1>
    </header>

    <div class="container">
        <h2 class="section-title">جميع المقالات</h2>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <img src="./uploaded_img/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo $row['title']; ?></h3>
                            <p class="card-description"><?php echo mb_substr($row['description'], 0, 100, 'UTF-8'); ?>...</p>
                            <a href="post_view.php?id=<?php echo $row['id']; ?>">عرض المزيد</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>لا توجد مقالات حالياً.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>© 2025   - جميع الحقوق محفوظة</p>
    </footer>
</body>
</html>
