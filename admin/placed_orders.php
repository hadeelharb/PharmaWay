<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

if (isset($_POST['update_payment'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];
    $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
    $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_payment->execute([$payment_status, $order_id]);
    $message[] = 'تم تحديث حالة الدفع!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلبات</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../csss/admin_style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .orders {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .heading {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .box-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .box {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .box p {
            margin: 5px 0;
        }
        .option-btn, .delete-btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .option-btn {
            background-color: #28a745;
            color: white;
        }
        .option-btn:hover {
            background-color: #218838;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            margin-left: 10px;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .empty {
            text-align: center;
            color: #999;
        }
    </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">

    <h1 class="heading">الطلبات</h1>

    <div class="box-container">

        <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders`");
        $select_orders->execute();
        if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>تاريخ الطلب: <span><?= $fetch_orders['placed_on']; ?></span></p>
            <p>الاسم: <span><?= $fetch_orders['name']; ?></span></p>
            <p>رقم الهاتف: <span><?= $fetch_orders['number']; ?></span></p>
            <p>العنوان: <span><?= $fetch_orders['address']; ?></span></p>
            <p>إجمالي المنتجات: <span><?= $fetch_orders['total_products']; ?></span></p>
            <p>إجمالي السعر: <span>₪<?= $fetch_orders['total_price']; ?>/-</span></p>
            <p>طريقة الدفع: <span><?= $fetch_orders['method']; ?></span></p>
            <form action="" method="post">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                <select name="payment_status" class="select">
                    <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
                    <option value="pending">معلق</option>
                    <option value="completed">مكتمل</option>
                </select>
                <div class="flex-btn">
                    <input type="submit" value="تحديث" class="option-btn" name="update_payment">
                    <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('هل تريد حذف هذا الطلب؟');">حذف</a>
                </div>
            </form>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">لا توجد طلبات حتى الآن!</p>';
        }
        ?>

    </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>