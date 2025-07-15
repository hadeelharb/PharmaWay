<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $id_num = $_POST['id_num'];
   $id_num = filter_var($id_num, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE id_num = ? AND password = ?");
   $select_user->execute([$id_num, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:index.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>تسجيل الدخول</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="csss/style1.css">

</head>
<body>
   

<section class="form-container">

   <form action="" method="post">
      <h3> تسجيل الدخول</h3>
      <input type="text" name="id_num" required placeholder="ادخلرقم هويتك" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="ادخل كلمة المرور" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="تسجيل الدخول" class="btn" name="submit">
      <a href="./index.php" class="btn" style="background-color:red;">  الغاء</a>
      <span>في حال تريد انشاء حساب اضغط بالأسفل</span>
<a href="user_register.php" class="btn" style="background-color:gold;">انشاء حساب جديد</a>
   </form>

</section>














<script src="js/script.js"></script>

</body>
</html>