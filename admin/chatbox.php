<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

// إضافة رد جديد
if (isset($_POST['add_response'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("INSERT INTO `chat_responses` (question, answer) VALUES (?, ?)");
    $stmt->execute([$question, $answer]);
    $message[] = 'تم إضافة الرد بنجاح!';
}

// تحديث رد موجود
if (isset($_POST['update_response'])) {
    $response_id = $_POST['response_id'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("UPDATE `chat_responses` SET question = ?, answer = ? WHERE id = ?");
    $stmt->execute([$question, $answer, $response_id]);
    $message[] = 'تم تحديث الرد بنجاح!';
}

// حذف رد
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_response = $conn->prepare("DELETE FROM `chat_responses` WHERE id = ?");
    $delete_response->execute([$delete_id]);
    header('location:chat_responses.php');
    exit;
}

// استعلام لجلب الردود
$select_responses = $conn->prepare("SELECT * FROM `chat_responses`");
$select_responses->execute();
$responses = $select_responses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة ردود الشات</title>
    <link rel="stylesheet" href="../csss/admin_style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .response-item {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>إدارة ردود الشات</h1>

    <!-- نموذج إضافة رد -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="question">السؤال:</label>
            <input type="text" name="question" id="question" required>
        </div>
        <div class="form-group">
            <label for="answer">الجواب:</label>
            <textarea name="answer" id="answer" rows="4" required></textarea>
        </div>
        <button type="submit" name="add_response">إضافة رد</button>
    </form>
    <!-- عرض الردود الموجودة -->
    <h2>الردود الحالية</h2>
    <?php
    if (!empty($responses)) {
        foreach ($responses as $response) {
    ?>
        <div class="response-item">
            <p><strong>السؤال:</strong> <?= htmlspecialchars($response['question']); ?></p>
            <p><strong>الجواب:</strong> <?= htmlspecialchars($response['answer']); ?></p>
            <form method="POST" action="">
                <input type="hidden" name="response_id" value="<?= $response['id']; ?>">
                <div class="form-group">
                    <label for="question">تعديل السؤال:</label>
                    <input type="text" name="question" value="<?= htmlspecialchars($response['question']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="answer">تعديل الجواب:</label>
                    <textarea name="answer" rows="4" required><?= htmlspecialchars($response['answer']); ?></textarea>
                </div>
                <button type="submit" name="update_response">تحديث</button>
                <a href="chat_responses.php?delete=<?= $response['id']; ?>" class="delete-btn" onclick="return confirm('هل تريد حذف هذا الرد؟');">حذف</a>
            </form>
        </div>
    <?php
        }
    } else {
        echo '<p>لا توجد ردود حالياً.</p>';
    }
    ?>
</div>

</body>
</html>