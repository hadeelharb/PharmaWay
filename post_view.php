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

// التحقق من وجود المعرف في الرابط
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo "المقال غير موجود!";
        exit;
    }
} else {
    echo "لم يتم تحديد المقال!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?></title>
    <link rel="stylesheet" href="./css/style.css"> <!-- استبدل بـ مسار ملف CSS الخاص بك -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
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
            max-width: 800px;
            margin: 20px auto;
            padding: 0 15px;
        }
        .post-image {
            text-align: center;
            margin: 20px 0;
        }
        .post-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .post-title {
            font-size: 28px;
            margin: 20px 0;
            text-align: center;
            color: #444;
        }
        .post-description {
            font-size: 18px;
            line-height: 1.8;
            color: #555;
            text-align: justify;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1> 

        Pharma way


        </h1>
    </header>

    <div class="container">
        <div class="post-image">
            <img src="./uploaded_img/<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>">
        </div>
        <h2 class="post-title"><?php echo $post['title']; ?></h2>
        <p class="post-description"><?php echo nl2br($post['description']); ?></p>
    </div>

    <footer>
        <p>© 2025   - جميع الحقوق محفوظة</p>
    </footer>
</body>
</html>
