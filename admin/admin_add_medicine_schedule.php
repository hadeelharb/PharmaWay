<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sr";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);
// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// معالجة إرسال النموذج
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $medicine_name = isset($_POST['medicine_name']) ? trim($_POST['medicine_name']) : '';
    $dosage = isset($_POST['dosage']) ? trim($_POST['dosage']) : '';

    if ($user_id > 0 && $medicine_name !== '' && $dosage !== '') {
        // إعداد وإرسال الاستعلام
        $stmt = $conn->prepare("INSERT INTO medicine_schedule (user_id, medicine_name, dosage) VALUES (?, ?, ?)");
        if ($stmt === false) {
            $message = "فشل في إعداد الاستعلام: " . $conn->error;
        } else {
            $stmt->bind_param("iss", $user_id, $medicine_name, $dosage);
            if ($stmt->execute()) {
                $message = "موعد أخذ الدواء تم إضافته بنجاح.";
            } else {
                $message = "خطأ في إضافة الموعد: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $message = "يرجى تعبئة جميع الحقول و اختيار المريض.";
    }
}

// جلب المستخدمين للاختيار
$users = [];
$sql = "SELECT * FROM users ORDER BY name ASC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>إضافة موعد أخذ الدواء</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f7f9fc;
        margin: 0;
        padding: 20px;
        direction: rtl;
        text-align: right;
        color: #333;
    }
    .container {
        max-width: 480px;
        margin: 0 auto;
        background: #fff;
        padding: 25px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h1 {
        margin-bottom: 24px;
        color: #2c3e50;
        text-align: center;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #34495e;
    }
    select, input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 18px;
        border: 1px solid #ccd0d5;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }
    select:focus, input[type="text"]:focus {
        border-color: #2980b9;
        outline: none;
    }
    button {
        width: 100%;
        padding: 12px;
        background: #2980b9;
        border: none;
        border-radius: 6px;
        color: white;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background: #1c5980;
    }
    .message {
        margin: 20px 0;
        padding: 12px;
        background: #dff0d8;
        color: #3c763d;
        border-radius: 5px;
        text-align: center;
        font-weight: 600;
    }
    .error-message {
        background: #f2dede;
        color: #a94442;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>إضافة موعد أخذ الدواء لمريض</h1>

        <?php if ($message): ?>
            <div class="message <?php echo (strpos($message,'خطأ') !== false) ? 'error-message' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="user_id">اختر المريض</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- اختر المريض --</option>
                <?php foreach($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user['id']); ?>">
                        <?php echo htmlspecialchars($user['user'] . " - ID: " . $user['id'] . " - الهوية: " . $user['id_num']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="medicine_name">اسم الدواء</label>
            <input type="text" id="medicine_name" name="medicine_name" required placeholder="أدخل اسم الدواء" />

            <label for="dosage">وصف الجرعة</label>
            <input type="text" id="dosage" name="dosage" required placeholder="مثل: 3 حبات بعد الغداء أو حبة قبل النوم" />

            <button type="submit">إضافة الموعد</button>
        </form>
    </div>
</body>
</html>