<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'العنوان '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'order placed successfully!';
   }else{
      $message[] = 'your cart is empty';
   }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout -  Pharm Away</title>
   
   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style1.css">

   <style>
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
         background-color: #f9f9f9;
      }
      header, footer {
         background-color: #6b4226;
         color: white;
         text-align: center;
         padding: 15px 0;
      }
      header a, footer a {
         color: white;
         text-decoration: none;
         font-weight: bold;
         margin: 0 10px;
      }
      header a:hover, footer a:hover {
         text-decoration: underline;
      }
      .checkout-orders {
         max-width: 800px;
         margin: 20px auto;
         padding: 20px;
         background: white;
         border-radius: 8px;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      }
      .checkout-orders h3 {
         color: #6b4226;
         margin-bottom: 15px;
      }
      .checkout-orders .display-orders {
         margin-bottom: 20px;
      }
      .checkout-orders .display-orders p {
         font-size: 16px;
         margin: 10px 0;
         color: #333;
      }
      .checkout-orders .display-orders .grand-total {
         font-size: 18px;
         font-weight: bold;
         color: #6b4226;
      }
      .checkout-orders .flex {
         display: flex;
         flex-wrap: wrap;
         gap: 15px;
      }
      .checkout-orders .inputBox {
         flex: 1 1 calc(50% - 15px);
      }
      .checkout-orders .inputBox span {
         font-size: 14px;
         color: #6b4226;
      }
      .checkout-orders .inputBox input, .checkout-orders .inputBox select {
         width: 100%;
         padding: 10px;
         margin-top: 5px;
         border: 1px solid #ddd;
         border-radius: 4px;
      }
      .checkout-orders .btn {
         background: #6b4226;
         color: white;
         padding: 10px 20px;
         border: none;
         border-radius: 4px;
         cursor: pointer;
      }
      .checkout-orders .btn.disabled {
         background: gray;
         cursor: not-allowed;
      }
      .checkout-orders .btn:hover {
         background: #8c5a38;
      }
      footer p {
         margin: 0;
         font-size: 14px;
      }
   </style>
</head>
<body>

<header>
   <a href="./index.php">الرئيسية</a>

</header>

<section class="checkout-orders">

   <form action="" method="POST">
      <h3><a href="./index.php" style="all:unset;">العودة للرئيسية</a></h3>

      <h3>طلباتي</h3>
      <div class="display-orders">
         <?php
            $grand_total = 0;
            $cart_items[] = '';
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if($select_cart->rowCount() > 0){
               while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                  $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
                  $total_products = implode($cart_items);
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
         ?>
            <p> <?= $fetch_cart['name']; ?> <span>(<?= '₪'.$fetch_cart['price'].' x '. $fetch_cart['quantity']; ?>)</span> </p>
         <?php
               }
            }else{
               echo '<p class="empty">السلة فارغة!</p>';
            }
         ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">السعر الكلي <span>₪<?= $grand_total; ?></span></div>
      </div>

      <h3>معلوماتي</h3>
      <div class="flex">
         <div class="inputBox">
            <span>الاسم</span>
            <input type="text" name="name" placeholder="الاسم الكامل" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>الهاتف</span>
            <input type="number" name="number" placeholder="رقم الهاتف" class="box" maxlength="10" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>البريد</span>
            <input type="email" name="email" placeholder="البريد الإلكتروني" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>ملاحظات إضافية</span>
            <input type="text" name="notes" placeholder="إضافة ملاحظات على الطلب" class="box" maxlength="200">
         </div>
         <div class="inputBox">
            <span>طريقة الدفع</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">الدفع عند الاستلام</option>
               <option value="jawal pay" disabled>جوال باي (قريباً)</option>
               <option value="visa" disabled>فيزا (قريباً)</option>
            </select>
         </div>
         <div class="inputBox">
            <span>العنوان (رقم الشقة)</span>
            <input type="text" name="flat" placeholder="رقم الشقة" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>الشارع</span>
            <input type="text" name="street" placeholder="اسم الشارع" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>المدينة</span>
            <input type="text" name="city" placeholder="المدينة" class="box" maxlength="50" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="تأكيد الطلب">
   </form>

</section>

<footer>
   <p>&copy; 2025  . جميع الحقوق محفوظة.</p>
</footer>

<script src="js/script.js"></script>

</body>
</html>
