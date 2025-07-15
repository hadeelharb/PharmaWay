<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

if (isset($_POST['add_post'])) {
    // الحصول على بيانات النموذج
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

    // معالجة رفع الصورة
    $image = $_FILES['post_image']['name'];
    $image_size = $_FILES['post_image']['size'];
    $image_tmp_name = $_FILES['post_image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    // التحقق من حجم الصورة
    if ($image_size > 20000000000000000000000000) {
        $message[] = 'حجم الصورة كبير جداً!';
    } else {
        move_uploaded_file($image_tmp_name, $image_folder);

        // إدخال المنشور في قاعدة البيانات
        $insert_post = $conn->prepare("INSERT INTO `posts`(title, description, image, date, category) VALUES(?, ?, ?, ?, ?)");
        $insert_post->execute([$title, $description, $image, date('Y-m-d'), $category]);

        if ($insert_post) {
            $message[] = 'تم إضافة منشور جديد!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المنشورات</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../csss/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-posts">

    <h1 class="heading">إضافة منشور</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="flex">
            <div class="inputBox">
                <span>عنوان المنشور</span>
                <input type="text" class="box" required maxlength="255" placeholder="أدخل عنوان المنشور" name="title">
            </div>

            <div class="inputBox">
                <span>صورة المنشور</span>
                <input type="file" class="box" name="post_image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>

            <div class="inputBox">
                <span>وصف المنشور</span>
                <textarea name="description" class="box" placeholder="أدخل وصف المنشور"></textarea>
            </div>

            <div class="inputBox">
                <span>فئة المنشور</span>
                <input type="text" class="box" placeholder="أدخل فئة المنشور" name="category">
            </div>
        </div>

        <input type="submit" value="إضافة منشور" class="btn" name="add_post">
    </form>

</section>

<section class="show-posts">

    <h1 class="heading">المنشورات</h1>

    <div class="box-container">

    <?php
        $select_posts = $conn->prepare("SELECT * FROM `posts`");
        $select_posts->execute();
        if ($select_posts->rowCount() > 0) {
            while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) { 
    ?>
    <div class="box">
        <img src="../uploaded_img/<?= $fetch_posts['image']; ?>" alt="">
        <div class="title"><?= $fetch_posts['title']; ?></div>
        <div class="description"><?= $fetch_posts['description']; ?></div>
        <div class="category"><?= $fetch_posts['category']; ?></div>
        <div class="date"><?= $fetch_posts['date']; ?></div>
        
        <div class="flex-btn">
            <a href="post.php?delete=<?= $fetch_posts['id']; ?>" class="delete-btn">حذف</a>
        </div>
    </div>
    <?php
            }
        } else {
            echo '<p class="empty">لا توجد منشورات مضافة بعد!</p>';
        }
    ?>
    
    </div>

</section>

<script src="../js/admin_script.js"></script>
</body>
</html>