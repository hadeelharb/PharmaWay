<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

if (isset($_POST['update'])) {

    $pid = $_POST['pid'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $review_count = filter_var($_POST['review_count'], FILTER_SANITIZE_NUMBER_INT);
    $old_price = filter_var($_POST['old_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $new_price = filter_var($_POST['new_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $discount_percentage = filter_var($_POST['discount_percentage'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $cat_id = filter_var($_POST['cat_id'], FILTER_SANITIZE_NUMBER_INT);
    $descr = filter_var($_POST['descr'], FILTER_SANITIZE_STRING);
    $prescription = filter_var($_POST['prescription'], FILTER_SANITIZE_STRING);
    $ingredients = filter_var($_POST['ingredients'], FILTER_SANITIZE_STRING);
    $usagee = filter_var($_POST['usagee'], FILTER_SANITIZE_STRING);
    $side_effects = filter_var($_POST['side_effects'], FILTER_SANITIZE_STRING);
    $dosage = filter_var($_POST['dosage'], FILTER_SANITIZE_STRING);
    $formm = filter_var($_POST['formm'], FILTER_SANITIZE_STRING);
    $manufacturer = filter_var($_POST['manufacturer'], FILTER_SANITIZE_STRING);
    $not_for = filter_var($_POST['not_for'], FILTER_SANITIZE_STRING);
    $manufacture_date = filter_var($_POST['manufacture_date'], FILTER_SANITIZE_STRING);
    $expiry_date = filter_var($_POST['expiry_date'], FILTER_SANITIZE_STRING);
    $weight = filter_var($_POST['weight'], FILTER_SANITIZE_STRING);

    // تحديث الصور إذا تم رفع صور جديدة
    $image_01 = $_FILES['image_01']['name'];
    $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
    $image_folder_01 = '../uploaded_img/' . $image_01;

    $image_02 = $_FILES['image_02']['name'];
    $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
    $image_folder_02 = '../uploaded_img/' . $image_02;

    // استعلام لتحديث المنتج
    $update_product = $conn->prepare("UPDATE `products` SET 
        title = ?, rating = ?, review_count = ?, old_price = ?, new_price = ?, 
        discount_percentage = ?, quantity = ?, cat_id = ?, descr = ?, prescription = ?, 
        ingredients = ?, usagee = ?, side_effects = ?, dosage = ?, formm = ?, 
        manufacturer = ?, not_for = ?, manufacture_date = ?, expiry_date = ?, weight = ? 
        WHERE id = ?");
    
    // تنفيذ الاستعلام
    $update_product->execute([
        $title, $rating, $review_count, $old_price, $new_price, $discount_percentage,
        $quantity, $cat_id, $descr, $prescription, $ingredients, $usagee, $side_effects,
        $dosage, $formm, $manufacturer, $not_for, $manufacture_date, $expiry_date, $weight, $pid
    ]);

    // تحديث الصور إذا تم رفع صور جديدة
    if (!empty($image_01)) {
        $update_image_01 = $conn->prepare("UPDATE `products` SET image_01 = ? WHERE id = ?");
        $update_image_01->execute([$image_01, $pid]);
        move_uploaded_file($image_tmp_name_01, $image_folder_01);
    }

    if (!empty($image_02)) {
        $update_image_02 = $conn->prepare("UPDATE `products` SET image_02 = ? WHERE id = ?");
        $update_image_02->execute([$image_02, $pid]);
        move_uploaded_file($image_tmp_name_02, $image_folder_02);
    }

    $message[] = 'تم تحديث المنتج بنجاح!';
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث المنتج</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../csss/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

    <h1 class="heading">تحديث المنتج</h1>

    <?php
    $update_id = $_GET['update'];
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $select_products->execute([$update_id]);
    if ($select_products->rowCount() > 0) {
        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) { 
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
        <span>اسم المنتج</span>
        <input type="text" name="title" required class="box" value="<?= $fetch_products['title']; ?>">
        <span>التقييم</span>
        <input type="number" name="rating" step="0.01" class="box" value="<?= $fetch_products['rating']; ?>">
        <span>عدد المراجعات</span>
        <input type="number" name="review_count" class="box" value="<?= $fetch_products['review_count']; ?>">
        <span>السعر القديم</span>
        <input type="number" name="old_price" step="0.01" class="box" value="<?= $fetch_products['old_price']; ?>">
        <span>السعر الجديد</span>
        <input type="number" name="new_price" step="0.01" class="box" value="<?= $fetch_products['new_price']; ?>">
        <span>نسبة الخصم</span>
        <input type="number" name="discount_percentage" step="0.01" class="box" value="<?= $fetch_products['discount_percentage']; ?>">
        <span>الكمية</span>
        <input type="number" name="quantity" class="box" value="<?= $fetch_products['quantity']; ?>">
        <span>الفئة</span>
        <input type="number" name="cat_id" class="box" value="<?= $fetch_products['cat_id']; ?>">
        <span>الوصف</span>
        <textarea name="descr" class="box"><?= $fetch_products['descr']; ?></textarea>
        <span>الوصفة الطبية</span>
        <input type="text" name="prescription" class="box" value="<?= $fetch_products['prescription']; ?>">
        <span>المكونات</span>
        <input type="text" name="ingredients" class="box" value="<?= $fetch_products['ingredients']; ?>">
        <span>طريقة الاستخدام</span>
        <input type="text" name="usagee" class="box" value="<?= $fetch_products['usagee']; ?>">
        <span>الآثار الجانبية</span>
        <input type="text" name="side_effects" class="box" value="<?= $fetch_products['side_effects']; ?>">
        <span>الجرعة</span>
        <input type="text" name="dosage" class="box" value="<?= $fetch_products['dosage']; ?>">
        <span>الشكل</span>
        <input type="text" name="formm" class="box" value="<?= $fetch_products['formm']; ?>">
        <span>الشركة المصنعة</span>
        <input type="text" name="manufacturer" class="box" value="<?= $fetch_products['manufacturer']; ?>">
        <span>غير مناسب لـ</span>
        <input type="text" name="not_for" class="box" value="<?= $fetch_products['not_for']; ?>">
        <span>تاريخ التصنيع</span>
        <input type="text" name="manufacture_date" class="box" value="<?= $fetch_products['manufacture_date']; ?>">
        <span>تاريخ انتهاء الصلاحية</span>
        <input type="text" name="expiry_date" class="box" value="<?= $fetch_products['expiry_date']; ?>">
        <span>الوزن</span>
        <input type="text" name="weight" class="box" value="<?= $fetch_products['weight']; ?>">

        <span>صورة المنتج 1 (اختياري)</span>
        <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
        <span>صورة المنتج 2 (اختياري)</span>
        <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">

        <div class="flex-btn">
            <input type="submit" name="update" class="btn" value="تحديث">
            <a href="products.php" class="option-btn">العودة</a>
        </div>
    </form>
    <?php
        }
    } else {
        echo '<p class="empty">لا يوجد منتج بهذا المعرف!</p>';
    }
    ?>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>