<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['send'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $mobile = filter_var($_POST['mobile'], FILTER_SANITIZE_STRING);
    $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

    $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND mobile = ? AND message = ?");
    $select_message->execute([$name, $email, $mobile, $msg]);

    if ($select_message->rowCount() > 0) {
        $message[] = 'الرسالة أُرسلت مسبقًا!';
    } else {
        $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, mobile, message) VALUES(?,?,?,?,?)");
        $insert_message->execute([$user_id, $name, $email, $mobile, $msg]);

        $message[] = 'تم إرسال الرسالة بنجاح!';
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اتصل بنا -  </title>
    
    <!-- روابط الخطوط والأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        header, footer {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ffa500;
        }
        .contact {
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
        }
        .contact h3 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #333;
        }
        .contact .box {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .contact .btn {
            background: #ffa500;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .contact .btn:hover {
            background: #e69500;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

<!-- الهيدر -->
<header>
    <div class="logo"> Pharma way</div>
</header>

<!-- قسم الاتصال -->
<section class="contact">
    <form action="" method="post">
        <h3>اترك بياناتك</h3>
        <input type="text" name="name" placeholder="اسمك" required maxlength="20" class="box">
        <input type="email" name="email" placeholder="بريدك" required maxlength="50" class="box">
        <input type="mobile" name="mobile" min="0" max="9999999999" placeholder="رقم هاتفك" required onkeypress="if(this.value.length == 10) return false;" class="box">
        <textarea name="msg" class="box" placeholder="رسالتك" cols="30" rows="10"></textarea>
        <input type="submit" value="ارسل" name="send" class="btn">
    </form>
</section>

<!-- الفوتر -->
<footer>
    <p>جميع الحقوق محفوظة ©   2025</p>
</footer>

<script src="js/script.js"></script>
</body>
</html>
