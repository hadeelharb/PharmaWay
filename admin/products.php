<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

// إضافة منتج جديد
if (isset($_POST['add_product'])) {
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

    $image_01 = $_FILES['image_01']['name'];
    $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
    $image_folder_01 = '../uploaded_img/' . $image_01;

    $image_02 = $_FILES['image_02']['name'];
    $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
    $image_folder_02 = '../uploaded_img/' . $image_02;

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE title = ?");
    $select_products->execute([$title]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product title already exists!';
    } else {
        $insert_products = $conn->prepare("INSERT INTO `products` 
            (title, image_01, image_02, descr, rating, review_count, old_price, new_price, discount_percentage, quantity, cat_id, prescription, ingredients, usagee, side_effects, dosage, formm, manufacturer, not_for, manufacture_date, expiry_date, weight) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_products->execute([
            $title, $image_01, $image_02, $descr, $rating, $review_count, $old_price, $new_price, $discount_percentage, $quantity, $cat_id, $prescription, $ingredients, $usagee, $side_effects, $dosage, $formm, $manufacturer, $not_for, $manufacture_date, $expiry_date, $weight
        ]);

        if ($insert_products) {
            if (move_uploaded_file($image_tmp_name_01, $image_folder_01) && 
            move_uploaded_file($image_tmp_name_02, $image_folder_02)) {
            $message[] = 'New product added successfully!';
        } else {
            $message[] = 'Failed to move uploaded file. Check folder permissions.';
        }
    }
}
}

// حذف منتج
if (isset($_GET['delete'])) {
$delete_id = $_GET['delete'];
$delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
$delete_product_image->execute([$delete_id]);
$fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);

if (unlink('../uploaded_img/' . $fetch_delete_image['image_01']) && unlink('../uploaded_img/' . $fetch_delete_image['image_02'])) {
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$delete_id]);
    $message[] = 'Product deleted successfully!';
} else {
    $message[] = 'Failed to delete product image!';
}
header('location:products.php');
exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>المنتجات</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link rel="stylesheet" href="../csss/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">
<h1 class="heading">إضافة المنتجات</h1>

<form action="" method="post" enctype="multipart/form-data">
    <div class="flex">
        <div class="inputBox">
            <span>اسم المنتج (مطلوب)</span>
            <input type="text" class="box" required maxlength="255" placeholder="أدخل اسم المنتج" name="title">
        </div>
        <div class="inputBox">
            <span>التقييم (مطلوب)</span>
            <input type="number" step="0.01" class="box" required min="0" max="5" placeholder="أدخل تقييم المنتج" name="rating">
        </div>
        <div class="inputBox">
            <span>عدد المراجعات (مطلوب)</span>
            <input type="number" class="box" required min="0" placeholder="أدخل عدد المراجعات" name="review_count">
        </div>
        <div class="inputBox">
            <span>السعر القديم</span>
            <input type="number" step="0.01" class="box" required min="0" placeholder="أدخل السعر القديم" name="old_price">
        </div>
        <div class="inputBox">
            <span>السعر الجديد</span>
            <input type="number" step="0.01" class="box" required min="0" placeholder="أدخل السعر الجديد" name="new_price">
        </div>
        <div class="inputBox">
            <span>نسبة الخصم</span>
            <input type="number" step="0.01" class="box" placeholder="أدخل نسبة الخصم" name="discount_percentage">
        </div>
        <div class="inputBox">
            <span>الكمية (مطلوب)</span>
            <input type="number" class="box" required min="0" placeholder="أدخل الكمية" name="quantity">
        </div>
        <div class="inputBox">
            <span>الفئة (مطلوب)</span>
            <select name="cat_id" class="box" required>
                <option value="" disabled selected>اختر الفئة</option>
                <?php
                $select_categories = $conn->prepare("SELECT * FROM `categories`");
                $select_categories->execute();
                if ($select_categories->rowCount() > 0) {
                    while ($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . $fetch_category['id'] . '">' . $fetch_category['category'] . '</option>';
                    }
                } else {
                    echo '<option value="">لا توجد فئات متاحة</option>';
                }
                ?>
            </select>
        </div>
        <div class="inputBox">
            <span>صورة المنتج 1 (مطلوب)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
            </div>
            <div class="inputBox">
                <span>صورة المنتج 2 (اختياري)</span>
                <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
            </div>
            <div class="inputBox">
                <span>الوصف (مطلوب)</span>
                <textarea name="descr" class="box" required maxlength="200" placeholder="أدخل وصف المنتج"></textarea>
            </div>
            <div class="inputBox">
                <span>الوصفة الطبية (مطلوب)</span>
                <input type="text" name="prescription" class="box" required maxlength="200" placeholder="أدخل الوصفة الطبية">
            </div>
            <div class="inputBox">
                <span>المكونات (مطلوب)</span>
                <input type="text" name="ingredients" class="box" required maxlength="200" placeholder="أدخل المكونات">
            </div>
            <div class="inputBox">
                <span>طريقة الاستخدام (مطلوب)</span>
                <input type="text" name="usagee" class="box" required maxlength="200" placeholder="أدخل طريقة الاستخدام">
            </div>
            <div class="inputBox">
                <span>الآثار الجانبية (مطلوب)</span>
                <input type="text" name="side_effects" class="box" required maxlength="200" placeholder="أدخل الآثار الجانبية">
            </div>
            <div class="inputBox">
                <span>الجرعة (مطلوب)</span>
                <input type="text" name="dosage" class="box" required maxlength="200" placeholder="أدخل الجرعة">
            </div>
            <div class="inputBox">
                <span>الشكل (مطلوب)</span>
                <input type="text" name="formm" class="box" required maxlength="200" placeholder="أدخل الشكل">
            </div>
            <div class="inputBox">
                <span>الشركة المصنعة (مطلوب)</span>
                <input type="text" name="manufacturer" class="box" required maxlength="200" placeholder="أدخل الشركة المصنعة">
            </div>
            <div class="inputBox">
                <span>غير مناسب لـ (مطلوب)</span>
                <input type="text" name="not_for" class="box" required maxlength="200" placeholder="أدخل غير مناسب لـ">
            </div>
            <div class="inputBox">
                <span>تاريخ التصنيع (مطلوب)</span>
                <input type="text" name="manufacture_date" class="box" required maxlength="200" placeholder="أدخل تاريخ التصنيع">
            </div>
            <div class="inputBox">
                <span>تاريخ انتهاء الصلاحية (مطلوب)</span>
                <input type="text" name="expiry_date" class="box" required maxlength="200" placeholder="أدخل تاريخ انتهاء الصلاحية">
            </div>
            <div class="inputBox">
                <span>الوزن (مطلوب)</span>
                <input type="text" name="weight" class="box" required maxlength="200" placeholder="أدخل الوزن">
            </div>
        </div>
        <input type="submit" value="إضافة منتج" class="btn" name="add_product">
    </form>
</section>

<section class="show-products">
    <h1 class="heading">جميع المنتجات</h1>
    <div class="box-container">
        <?php
        $select_products = $conn->prepare("SELECT * FROM `products`");
        $select_products->execute();
        if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) { 
        ?>
        <div class="box">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
            <?php if (!empty($fetch_products['image_02'])) : ?>
                <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
            <?php endif; ?>
            <div class="name"><?= $fetch_products['title']; ?></div>
            <div class="details">
                <p>الوصف: <?= $fetch_products['descr']; ?></p>
                <p>التقييم: <?= $fetch_products['rating']; ?> (<?= $fetch_products['review_count']; ?> مراجعة)</p>
                <p>الوصفة الطبية: <?= $fetch_products['prescription']; ?></p>
                <p>المكونات: <?= $fetch_products['ingredients']; ?></p>
                <p>طريقة الاستخدام: <?= $fetch_products['usagee']; ?></p>
                <p>الآثار الجانبية: <?= $fetch_products['side_effects']; ?></p>
                <p>الجرعة: <?= $fetch_products['dosage']; ?></p>
                <p>الشكل: <?= $fetch_products['formm']; ?></p>
                <p>الشركة المصنعة: <?= $fetch_products['manufacturer']; ?></p>
                <p>غير مناسب لـ: <?= $fetch_products['not_for']; ?></p>
                <p>تاريخ التصنيع: <?= $fetch_products['manufacture_date']; ?></p>
                <p>تاريخ انتهاء الصلاحية: <?= $fetch_products['expiry_date']; ?></p>
                <p>الوزن: <?= $fetch_products['weight']; ?></p>
            </div>
            <div class="price">السعر القديم: <?= $fetch_products['old_price']; ?> | السعر الجديد: <?= $fetch_products['new_price']; ?></div>
            <div class="flex-btn">
                <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">تحديث</a>
                <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('هل تريد حذف هذا المنتج؟');">حذف</a>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">لا توجد منتجات مضافة بعد!</p>';
        }
        ?>
    </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>