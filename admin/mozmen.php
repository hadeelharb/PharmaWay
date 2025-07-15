<?php
include '../components/connect.php';

session_start();

// تحقق مما إذا كان المستخدم هو المسؤول
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php'); // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول إذا لم يكن مسؤولاً
}

// استعلام لجلب جميع طلبات الأدوية
$select_requests = $conn->prepare("SELECT * FROM medicine_requests");
$select_requests->execute();
$requests = $select_requests->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة طلبات الأدوية</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 10px 15px;
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

<div class="container">
    <h3>إدارة طلبات الأدوية</h3>
    <table>
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>اسم المستخدم</th>
                <th>رقم المستخدم</th>
                <th>عنوان المستخدم</th>
                <th>اسم الدواء</th>
                <th>العيار</th>
                <th>الورقة الطبية</th>
                <th>تاريخ الطلب</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($requests) {
                foreach ($requests as $request) {
                    // استعلام لجلب اسم المستخدم بناءً على user_id
                    $select_user = $conn->prepare("SELECT * FROM medicine_requests WHERE id = ?");
                    $select_user->execute([$request['user_id']]);
                    $user = $select_user->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
                <td><?= $request['id']; ?></td>
                <td><?= $user['patient_name']; ?></td>
                <td><?= $user['contact_number']; ?></td>
                <td><?= $user['delivery_address']; ?></td>
                <td><?= $request['medicine_name']; ?></td>
                
                <td><?= $request['dosage']; ?></td>
                <td><a href="../uploads/prescriptions/<?= $request['prescription']; ?>" target="_blank">عرض</a></td>
                <td><?= $request['created_at']; ?></td>
            </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="6">لا توجد طلبات حالياً.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script src="js/script.js"></script>

</body>
</html>