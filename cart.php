


<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_products'])) {
        // المنتجات المختارة لعملية الشراء
        $selected_products = $_POST['selected_products'];
        $selected_ids = implode(",", array_map('intval', $selected_products));

        // تنفيذ عملية الشراء (يمكن تعديل هذه العملية حسب النظام لديك)
        echo "<script>alert('تمت عملية الشراء بنجاح للمنتجات: [$selected_ids]!');</script>";
    } else {
        echo '<script>alert("لم يتم اختيار أي منتج!");</script>';
    }
}

if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$cart_id]);
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);
    echo '<script>alert("تم تحديث كمية المنتج");</script>';
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>سلة المشتريات</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="csss/style1.css">
   
   <style>
      body {
         font-family: 'Poppins', sans-serif;
         background-color: #f4f4f9;
         margin: 0;
         padding: 0;
      }

      header {
         background-color: #333;
         color: #fff;
         padding: 15px 0;
         text-align: center;
         font-size: 24px;
         margin-bottom: 20px;
      }

      footer {
         background-color: #333;
         color: #fff;
         text-align: center;
         padding: 20px;
         margin-top: 40px;
      }

      .products {
         padding: 20px;
      }

      .heading {
         text-align: center;
         font-size: 30px;
         margin-bottom: 30px;
         color: #333;
      }

      .table-container {
         overflow-x: auto;
         margin-top: 20px;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         text-align: center;
      }

      th, td {
         padding: 15px;
         border: 1px solid #ddd;
      }

      th {
         background-color: #333;
         color: white;
      }

      td img {
         width: 100px;
         height: auto;
      }

      .delete-btn {
         background-color: #ff4d4d;
         color: white;
         border: none;
         padding: 8px 15px;
         border-radius: 5px;
         cursor: pointer;
      }

      .delete-btn:hover {
         background-color: #ff1a1a;
      }

      .cart-total {
         background-color: #fff;
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
         text-align: center;
         margin-top: 20px;
      }

      .cart-total p {
         font-size: 20px;
         color: #333;
         margin-bottom: 20px;
      }

      .cart-total .btn {
         padding: 10px 20px;
         background-color: #28a745;
         color: white;
         border: none;
         border-radius: 5px;
         cursor: pointer;
      }

      .cart-total .btn:hover {
         background-color: #218838;
      }

      .cart-total .delete-btn {
         background-color: #ff4d4d;
         color: white;
         border: none;
         padding: 10px 20px;
         border-radius: 5px;
         cursor: pointer;
      }

      .cart-total .delete-btn:hover {
         background-color: #ff1a1a;
      }
   </style>
   <script>
      function toggleSelectAll(source) {
          const checkboxes = document.querySelectorAll('input[name="selected_products[]"]');
          checkboxes.forEach(checkbox => checkbox.checked = source.checked);
          updateTotal();
      }

      function updateTotal() {
          let total = 0;
          document.querySelectorAll('input[name="selected_products[]"]:checked').forEach(item => {
              const price = parseFloat(item.dataset.price);
              const quantity = parseInt(item.dataset.quantity);
              total += price * quantity;
          });
          document.getElementById('grand-total').innerText = `₪${total.toFixed(2)}`;
      }
   </script>
</head>
<body>

<header> Pharma way</header>

<section class="products shopping-cart">
   <h3 class="heading">سلة المشتريات</h3>

   <form action="" method="post" class="table-container">
      <table>
         <thead>
            <tr>
               <th><input type="checkbox" onclick="toggleSelectAll(this)"></th>
               <th>الصورة</th>
               <th>الاسم</th>
               <th>السعر</th>
               <th>الكمية</th>
               <th>السعر الكلي</th>
               <th>تاريخ الاضافة</th>
               <th>الإجراءات</th>
            </tr>
         </thead>
         <tbody>
         <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
               $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
               $grand_total += $sub_total;
         ?>
            <tr>
               <td>
                  <input type="checkbox" name="selected_products[]" value="<?= $fetch_cart['id']; ?>" data-price="<?= $fetch_cart['price']; ?>" data-quantity="<?= $fetch_cart['quantity']; ?>" onchange="updateTotal()">
               </td>
               <td><img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt=""></td>
               <td><?= $fetch_cart['name']; ?></td>
               <td>₪<?= $fetch_cart['price']; ?></td>
               <td><?= $fetch_cart['quantity']; ?></td>
               <td>₪<?= $sub_total; ?></td>
               <td><?= $fetch_cart['date']; ?></td>

               <td>
                  <form action="" method="post">
                     <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                     <button type="submit" class="delete-btn" name="delete" onclick="return confirm('هل تريد حذف هذا العنصر؟');">حذف</button>
                  </form>
               </td>
            </tr>
         <?php
            }
         } else {
            echo '<tr><td colspan="7">السلة فارغة</td></tr>';
         }
         ?>
         </tbody>
      </table>

      <div class="cart-total">
         <p>السعر الكلي: <span id="grand-total">₪<?= $grand_total; ?></span></p>
         <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('هل تريد الحذف؟');">حذف الكل</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">اتمام عملية الشراء</a>
      <a href="./index.php" class="btn ">الرجوع لصفحة الرئيسية</a>
   </div>      </div>
   </form>
</section>

<footer>حقوق الطبع والنشر &copy; 2025  . جميع الحقوق محفوظة.</footer>

</body>
</html>
