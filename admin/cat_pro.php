<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_cat'])){

   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $dsecripton = $_POST['dsecripton'];
   $dsecripton = filter_var($dsecripton, FILTER_SANITIZE_STRING);  


   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder= '../uploaded_img/'.$image;


   $select_cat = $conn->prepare("SELECT * FROM `categories` WHERE category = ?");
   $select_cat->execute([$category]);

   if($select_cat->rowCount() > 0){
      $message[] = 'category name already exist!';
   }else{

      $insert_cat = $conn->prepare("INSERT INTO `categories`(category,  image , dsecripton ) VALUES(?,?,?)");
      $insert_cat->execute([$category, $image, $dsecripton]);

      if($insert_cat){
         if($image_size > 200000000 OR $image_size> 200000000 OR $image_size> 200000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            
            $message[] = 'تم اضافة الصنف بنجاح';
         }

      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `categories` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);

   $delete_product = $conn->prepare("DELETE FROM `categories` WHERE id = ?");
   $delete_product->execute([$delete_id]);
  
   header('location:cat_pro.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>الاصناف</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../csss/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">

   <h1 class="heading">اضافة اصناف</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>اسم الصنف</span>
            <input type="text" class="box" required maxlength="100" placeholder="اسم الصنف" name="category">
         </div>

        <div class="inputBox">
            <span>صورة الصنف </span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
        <div class="inputBox">
            <span>وصف الصنف</span>
            <input type="text" class="box" required maxlength="100" placeholder="وصف الصنف" name="dsecripton">
         </div>
    
      </div>
      
      <input type="submit" value="اضافة الصنف" class="btn" name="add_cat">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">الاصناف المضافة</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `categories`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['category']; ?></div>
     
      <div class="flex-btn">
         <a href="cat_pro.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" >حذف</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">لا اصناف مضافة</p>';
      }
   ?>
   
   </div>

</section>








<script src="../jss/admin_script.js"></script>
   
</body>
</html>