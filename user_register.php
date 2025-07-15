<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $user = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
    $mobile = filter_var($_POST['mobile'], FILTER_SANITIZE_STRING);
    $id_num = filter_var($_POST['id_num'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING); // تصحيح "addrees" إلى "address"
    $age = filter_var($_POST['age'], FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    
    if ($select_user->rowCount() > 0) {
        $message[] = 'البريد الإلكتروني موجود بالفعل!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'تأكيد كلمة المرور غير متطابق!';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password, age, address, user, id_num, mobile) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_user->execute([$name, $email, $cpass, $age, $address, $user, $id_num, $mobile]);
            $message[] = 'تم التسجيل بنجاح، يرجى تسجيل الدخول الآن!';
            header('location:index.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب</title>
    
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="csss/style1.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .form-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .inputBox {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .inputBox input {
            width: 48%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #218838;
        }
        .cancel-btn {
            background-color: #dc3545;
        }
        .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<section class="form-container">
    <form action="" method="post">
        <h3>إنشاء حساب الآن</h3>
        <div class="inputBox">
            <input type="text" name="user" required placeholder="الاسم الرباعي" maxlength="20" class="box">
            <input type="text" name="id_num" required placeholder="رقم الهوية" maxlength="20" class="box">
        </div>
        <div class="inputBox">
            <input type="text" name="mobile" required placeholder="الهاتف" maxlength="20" class="box">
            <input type="text" name="age" required placeholder="العمر" maxlength="20" class="box">
        </div>
        <div class="inputBox">
            <input type="text" name="address" required placeholder="العنوان" maxlength="50" class="box"> <!-- تصحيح "addrees" إلى "address" -->
            <input type="text" name="chronic_disease" placeholder="هل تعاني من مرض مزمن؟ (نعم/لا)" maxlength="20" class="box">
        </div>
        <div class="inputBox">
            <input type="text" name="allergy" placeholder="هل تعاني من حساسية؟ (نعم/لا)" maxlength="20" class="box">
            <input type="text" name="gender" placeholder="الجنس" maxlength="20" class="box">
        </div>
        <div class="inputBox">
            <input type="email" name="email" required placeholder="الايميل" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="pass" required placeholder="كلمة السر" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <div class="inputBox">
            <input type="password" name="cpass" required placeholder="تأكيد كلمة السر" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <input type="submit" value="إنشاء الحساب" class="btn" name="submit">
        <button type="button" class="btn cancel-btn" onclick="window.history.back();">إلغاء</button>
    </form>
</section>

<script src="js/script.js"></script>

</body>
</html>