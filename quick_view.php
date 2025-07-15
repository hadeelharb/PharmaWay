<?php
include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';

include 'components/wishlist_cart.php';

$pid = $_GET['pid'];
$select_place = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
$select_place->execute([$pid]);

if($select_place->rowCount() > 0){
   while($fetch_place = $select_place->fetch(PDO::FETCH_ASSOC)){
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>عرض المنتج</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600|Poppins:300,400,500,600" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f7f7f7;
    }
    header {
      background-color: #333;
      color: #fff;
      padding: 12px;
      text-align: center;
      font-size: 24px;
    }
    .product-info span {
      font-weight: bold;
    }
    .price {
      font-size: 22px;
      color: #e67e22;
      margin: 15px 0;
    }
    .qty {
      width: 70px;
      margin: 0 10px;
    }
    .btn, .option-btn {
      padding: 10px 20px;
      margin: 5px 10px 5px 0;
      border-radius: 5px;
      border: none;
      cursor: pointer;
    }
    .btn {
      background-color: #ff7f3f;
      color: white;
    }
    .btn:hover {
      background-color: #ff5e21;
    }
    .option-btn {
      background-color: #4CAF50;
      color: white;
    }
    .option-btn:hover {
      background-color: #388e3c;
    }
    .swiper {
      width: 100%;
      padding-top: 20px;
    }
    .swiper-slide img {
      width: 100%;
      border-radius: 10px;
    }
    .product-section {
      background-color: #fff;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .details-section h4 {
      margin-top: 20px;
      color: #444;
    }
  </style>
</head>

<body>

<header>
    <a href="./index.php">Pharma way</a>
</header>
<main class="container mt-4">
  <form method="post">
    <input type="hidden" name="pid" value="<?= $fetch_place['id']; ?>">
    <input type="hidden" name="name" value="<?= $fetch_place['title']; ?>">
    <input type="hidden" name="price" value="<?= $fetch_place['new_price']; ?>">
    <input type="hidden" name="image" value="<?= $fetch_place['image_01']; ?>">

    <!-- صور المنتج -->
    <div class="product-section">
      <div class="row">
        <div class="col-lg-6">
          <div class="swiper mySwiper">
            <div class="swiper-wrapper">
              <?php if(!empty($fetch_place['image_01'])): ?>
              <div class="swiper-slide">
                <a href="uploaded_img/<?= $fetch_place['image_01']; ?>" data-lightbox="product-gallery">
                  <img src="uploaded_img/<?= $fetch_place['image_01']; ?>" alt="Image 1">
                </a>
              </div>
              <?php endif; ?>
              <?php if(!empty($fetch_place['image_02'])): ?>
              <div class="swiper-slide">
                <a href="uploaded_img/<?= $fetch_place['image_02']; ?>" data-lightbox="product-gallery">
                  <img src="uploaded_img/<?= $fetch_place['image_02']; ?>" alt="Image 2">
                </a>
              </div>
              <?php endif; ?>
              <?php if(!empty($fetch_place['image_03'])): ?>
              <div class="swiper-slide">
                <a href="uploaded_img/<?= $fetch_place['image_03']; ?>" data-lightbox="product-gallery">
                  <img src="uploaded_img/<?= $fetch_place['image_03']; ?>" alt="Image 3">
                </a>
              </div>
              <?php endif; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
          </div>
        </div>

        <!-- معلومات أساسية -->
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><span>اسم المنتج:</span> <?= $fetch_place['title']; ?></li>
            <li class="list-group-item"><span>العيار / الحجم:</span> <?= $fetch_place['dosage']; ?> </li>
            <li class="list-group-item"><span>نوع المنتج:</span> <?= $fetch_place['formm']; ?></li>
            <li class="list-group-item"><span>الشركة المصنعة:</span> <?= $fetch_place['manufacturer']; ?></li>
            <li class="list-group-item"><span>لا يصح استخدامه من قبل:</span> <?= $fetch_place['not_for']; ?></li>
            <li class="list-group-item"><span>تاريخ الإنتاج:</span> <?= $fetch_place['manufacture_date']; ?></li>
            <li class="list-group-item"><span>تاريخ الانتهاء:</span> <?= $fetch_place['expiry_date']; ?></li>
            <li class="list-group-item"><span>وزن العلبة:</span> <?= $fetch_place['weight']; ?> جم</li>
            <li class="list-group-item"><span>السعر القديم:</span> <?= $fetch_place['old_price']; ?> شيكل</li>
            <li class="list-group-item"><span>السعر الجديد:</span> <?= $fetch_place['new_price']; ?> شيكل</li>
          </ul>
          <div class="price">₪<?= $fetch_place['new_price']; ?></div>
          <input type="number" name="qty" class="qty" min="1" max="<?= $fetch_place['quantity']; ?>" value="1">
          <div>
            <input type="submit" name="add_to_cart" class="btn" value="أضف إلى السلة">
          </div>
        </div>
      </div>
    </div>

    <!-- التفاصيل الإضافية -->
    <div class="product-section details-section">
      <h4>الوصفة الطبية</h4>
      <p><?= $fetch_place['prescription']; ?></p>

      <h4>المكونات</h4>
      <p><?= $fetch_place['ingredients']; ?></p>

      <h4>طريقة الاستعمال</h4>
      <p><?= $fetch_place['usagee']; ?></p>
      <h4> الوصف</h4>
      <p><?= $fetch_place['descr']; ?></p>

      <h4>الأضرار الجانبية</h4>
      <p><?= !empty($fetch_place['side_effects']) ? $fetch_place['side_effects'] : 'لا توجد أضرار جانبية معروفة.'; ?></p>
    </div>
  </form>
</main>

<!-- سكربتات -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
  var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 10,
    loop: true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
</script>

</body>
</html>
<?php
   }
} else {
  echo '<p class="text-center mt-5">لا يوجد منتج بهذا المعرف!</p>';
}
?>
