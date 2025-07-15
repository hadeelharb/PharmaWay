<?php
include 'components/connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="ar">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>طلباتي</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      body {
         background: #f9f9f9;
         font-family: 'Tajawal', sans-serif;
         direction: rtl;
         text-align: right;
      }

      .orders {
         padding: 40px 20px;
         max-width: 1000px;
         margin: auto;
      }

      .heading {
         font-size: 32px;
         color: #333;
         margin-bottom: 30px;
         text-align: center;
      }

      .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 20px;
      }

      .box {
         background: #fff;
         border-radius: 15px;
         padding: 20px;
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
         transition: 0.3s;
      }

      .box:hover {
         transform: translateY(-5px);
      }

      .box p {
         margin: 10px 0;
         font-size: 15px;
         color: #555;
      }

      .box p span {
         color: #222;
         font-weight: bold;
      }

      .box p i {
         color: #ff6600;
         margin-left: 6px;
      }

      .empty {
         text-align: center;
         color: #999;
         font-size: 18px;
         margin-top: 40px;
      }
   </style>
</head>
<body>

<section class="orders">
   <h1 class="heading">طلبـــــاتي</h1>

   <div class="box-container">
      <?php
      if ($user_id == '') {
         echo '<p class="empty">الرجاء تسجيل الدخول لعرض الطلبات</p>';
      } else {
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY placed_on DESC");
         $select_orders->execute([$user_id]);
         if ($select_orders->rowCount() > 0) {
            while ($order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <div class="box">
                  <p><i class="fa-regular fa-calendar-days"></i>التاريخ: <span><?= $order['placed_on']; ?></span></p>
                  <p><i class="fa-regular fa-user"></i>الاسم: <span><?= $order['name']; ?></span></p>
                  <p><i class="fa-regular fa-envelope"></i>البريد: <span><?= $order['email']; ?></span></p>
                  <p><i class="fa-solid fa-phone"></i>الرقم: <span><?= $order['number']; ?></span></p>
                  <p><i class="fa-solid fa-location-dot"></i>العنوان: <span><?= $order['address']; ?></span></p>
                  <p><i class="fa-solid fa-money-bill-wave"></i>طريقة الدفع: <span><?= $order['method']; ?></span></p>
                  <p><i class="fa-solid fa-box"></i>الطلبية: <span><?= $order['total_products']; ?></span></p>
                  <p><i class="fa-solid fa-coins"></i>السعر الكلي: <span>₪<?= $order['total_price']; ?></span></p>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">لم تقم بأي طلبية بعد</p>';
         }
      }
      ?>
   </div>
</section>

<script src="js/script.js"></script>
</body>
</html>
