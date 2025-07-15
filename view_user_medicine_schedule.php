<?php
// Database connection settings - adjust as needed
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sr";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from query parameter
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

$user = null;
$medicine_schedules = [];

// Fetch user details
if ($user_id > 0) {
    $stmtUser = $conn->prepare("SELECT id, user, id_num FROM users WHERE id = ?");
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    if ($resultUser->num_rows > 0) {
        $user = $resultUser->fetch_assoc();
    }
    $stmtUser->close();

    // Fetch medicine schedule for the user
    $stmtSched = $conn->prepare("SELECT medicine_name, dosage FROM medicine_schedule WHERE user_id = ? ORDER BY schedule_id DESC");
    $stmtSched->bind_param("i", $user_id);
    $stmtSched->execute();
    $resultSched = $stmtSched->get_result();
    while ($row = $resultSched->fetch_assoc()) {
        $medicine_schedules[] = $row;
    }
    $stmtSched->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>تفاصيل مواعيد الدواء للمريض</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f7f9fc;
        margin: 0;
        padding: 20px;
        color: #333;
        direction: rtl;
        text-align: right;
    }
    .container {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 25px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h1, h2 {
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
    }
    th {
        background-color: #2980b9;
        color: white;
        text-align: right;
    }
    tr:hover {
        background-color: #f1f7fb;
    }
    .no-data {
        text-align: center;
        padding: 20px;
        color: #777;
        font-size: 16px;
    }
    a.button {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #2980b9;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    a.button:hover {
        background-color: #1c5980;
    }
</style>
</head>
<body>
    <div class="container">
        <?php if ($user): ?>
            <h1>تفاصيل مواعيد الدواء للمريض</h1>
            <h2><?php echo htmlspecialchars($user['user']); ?> (الهوية: <?php echo htmlspecialchars($user['id_num']); ?>)</h2>

            <?php if (!empty($medicine_schedules)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>اسم الدواء</th>
                            <th>وصف الجرعة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicine_schedules as $schedule): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($schedule['medicine_name']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['dosage']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">لا توجد مواعيد دواء لهذا المريض.</div>
            <?php endif; ?>


        <?php else: ?>
            <h1>المريض غير موجود</h1>
            <div class="no-data">يرجى تحديد مريض صحيح لعرض التفاصيل.</div>
        <?php endif; ?>
    </div>
</body>
</html>
