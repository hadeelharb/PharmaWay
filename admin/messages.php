<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_message->execute([$delete_id]);
    header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرسائل</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../csss/admin_style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .contacts {
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
        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
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

<section class="contacts">

    <h1 class="heading">الرسائل</h1>

    <div class="box-container">

        <?php
        $select_messages = $conn->prepare("SELECT * FROM `messages`");
        $select_messages->execute();
        if ($select_messages->rowCount() > 0) {
            while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>معرف المستخدم: <span><?= $fetch_message['user_id']; ?></span></p>
            <p>الاسم: <span><?= $fetch_message['name']; ?></span></p>
            <p>البريد الإلكتروني: <span><?= $fetch_message['email']; ?></span></p>
            <p>رقم الهاتف: <span><?= $fetch_message['mobile']; ?></span></p>
            <p>الرسالة: <span><?= $fetch_message['message']; ?></span></p>
            <a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('هل تريد حذف هذه الرسالة؟');" class="delete-btn">حذف</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">لا توجد رسائل لديك</p>';
        }
        ?>

    </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>