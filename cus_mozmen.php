<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php'); // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول إذا لم يكن مسجلاً الدخول
}

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $medicine_name = filter_var($_POST['medicine_name'], FILTER_SANITIZE_STRING);
    $dosage = filter_var($_POST['dosage'], FILTER_SANITIZE_STRING);
    $patient_name = filter_var($_POST['patient_name'], FILTER_SANITIZE_STRING);
    $contact_number = filter_var($_POST['contact_number'], FILTER_SANITIZE_STRING);
    $delivery_address = filter_var($_POST['delivery_address'], FILTER_SANITIZE_STRING);
    $prescription = $_FILES['prescription'];

    // تحقق من رفع الملف
    if ($prescription['error'] == 0) {
        $prescription_name = time() . '_' . $prescription['name'];
        $prescription_path = 'uploads/prescriptions/' . $prescription_name;

        // نقل الملف إلى المجلد المحدد
        move_uploaded_file($prescription['tmp_name'], $prescription_path);

        // إدخال الطلب في قاعدة البيانات
        $insert_request = $conn->prepare("INSERT INTO medicine_requests (user_id, medicine_name, dosage, patient_name, contact_number, delivery_address, prescription) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_request->execute([$user_id, $medicine_name, $dosage, $patient_name, $contact_number, $delivery_address, $prescription_name]);

        $message[] = 'تم تقديم طلب الدواء بنجاح!';
    } else {
        $message[] = 'حدث خطأ أثناء رفع الملف. يرجى المحاولة مرة أخرى.';
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب دواء مزمن</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .form-container {
            max-width: 600px;
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
            margin-bottom: 15px;
        }
        .inputBox input, .inputBox textarea {
            width: 100%;
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
    </style>
</head>
<body>

<section class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>طلب دواء مزمن</h3>
        <div class="inputBox">
            <input type="text" name="patient_name" required placeholder="الاسم الرباعي للمريض" maxlength="100">
        </div>
        <div class="inputBox">
            <input type="text" name="contact_number" required placeholder="رقم التواصل" maxlength="15">
        </div>
        <div class="inputBox">
            <input type="text" name="delivery_address" required placeholder="عنوان التوصيل" maxlength="255">
        </div>
        <div class="inputBox">
            <input type="text" name="medicine_name" required placeholder="اسم الدواء" maxlength="100">
        </div>
        <div class="inputBox">
            <input type="text" name="dosage" required placeholder="العيار المطلوب" maxlength="50">
        </div>
        <div class="inputBox">
            <label for="prescription">إرفاق ورقة طبية (PDF أو صورة):</label>
            <input type="file" name="prescription" accept=".pdf, .jpg, .jpeg, .png" required>
        </div>
        <input type="submit" value="تقديم الطلب" class="btn" name="submit">
    </form>
</section>

<script src="js/script.js"></script>

</body>
</html>