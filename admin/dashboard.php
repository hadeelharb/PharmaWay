<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>  لوحة ادارة الصيدلية</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../csss/admin_style.css">


<?php include '../components/admin_header.php'; ?>



  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الادمن</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            transition: width 0.3s;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            margin: 5px 0;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .main-content h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .responsive-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }

            .sidebar h2 {
                display: none;
            }

            .sidebar a {
                text-align: center;
                padding: 10px;
            }

            .main-content {
                padding: 10px;
            }

            .responsive-toggle {
                display: block;
                background-color: #333;
                color: white;
                padding: 10px;
                cursor: pointer;
                text-align: center;
                border: none;
                font-size: 18px;
            }

            .sidebar.open {
                width: 250px;
            }
        }
    </style>
</head>
<body>
    <button class="responsive-toggle" onclick="toggleSidebar()">☰</button>
    <div class="container">
        <div class="sidebar" id="sidebar">
            <h2>لوحة التحكم</h2>
            <a href="./dashboard.php" target="_blank">الرئيسية</a>
            <a href="./cat_pro.php" target="_blank">الاصناف</a>
            <a href="./products.php" target="_blank">المنتجات</a>
            <a href="./placed_orders.php" target="_blank">الطلبيات</a>
            <a href="./pharmay.php" target="_blank">الصيدليات المناوبة</a>
            <a href="./mozmen.php" target="_blank"> ادارة الادوية المزمنة</a>
            <a href="./news.php" target="_blank"> ادارة العاجل</a>
            <a href="./admin_voice_entries.php" target="_blank"> ادارة المساعد الصوتي</a>
            <a href="./admin_add_medicine_schedule.php" target="_blank"> ادارة اضافة ادوية مزمنة</a>
            <a href="./post.php" target="_blank">منشورات الموقع</a>
            <a href="./messages.php" target="_blank"> الدعم الفني</a>
            <a href="./chatbox.php" target="_blank">  ادارة الشات بوكس</a>
            <a href="../components//admin_logout.php">تسجيل الخروج</a>
        </div>
        <div class="main-content">
            <h1>مرحباً بك في لوحة تحكم الادمن</h1>
            <p>هذه الصفحة تتكيف مع جميع الشاشات وتوفر ازرار جانبية للتحكم بالمحتوى.</p>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
</body>
</html>
