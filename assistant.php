<?php
// اتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "sr");
$conn->set_charset("utf8"); // دعم اللغة العربية

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = trim($_POST["message"]);

    // البحث في قاعدة البيانات
    $stmt = $conn->prepare("SELECT answer FROM chat_responses WHERE question LIKE ?");
    $search = "%" . $message . "%";
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $stmt->bind_result($response);

    if ($stmt->fetch()) {
        echo $response;
    } else {
        echo "عذرًا، لم أفهم سؤالك. حاول كتابته بطريقة مختلفة أو تواصل معنا عبر الواتساب.";
    }

    $stmt->close();
}

$conn->close();
?>
