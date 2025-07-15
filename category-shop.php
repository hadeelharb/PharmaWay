<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>  pharma way </title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Baloo+Chettan|Poppins:400,600,700&display=swap" rel="stylesheet">
  
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .header_section {
      background-color: #222;
      color: #fff;
      padding: 15px 0;
      text-align: center;
      font-size: 28px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .hero_area {
      background-color: #f4f4f4;
      padding: 30px 0;
    }

    .navbar-brand span {
      color: #d4af37;
      font-size: 32px;
      font-weight: 700;
    }

    .item_section {
      padding: 50px 0;
    }

    .item_container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      justify-items: center;
      padding: 0 20px;
    }

    .box {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
      overflow: hidden;
      transition: all 0.3s ease;
      height: 100%;
    }

    .box:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .img-box img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-bottom: 2px solid #d4af37;
      transition: transform 0.3s ease;
    }

    .img-box:hover img {
      transform: scale(1.1);
    }

    .name h5 {
      color: #333;
      font-size: 20px;
      font-weight: 600;
      margin: 20px 0;
      text-transform: uppercase;
    }

    .name a {
      text-decoration: none;
    }

    footer {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 20px 0;
      margin-top: 50px;
    }

    footer p {
      margin: 0;
    }

    @media (max-width: 991px) {
      .item_container {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .item_container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body class="sub_page">

  <!-- Header Section -->
  <div class="hero_area">
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="index.php">
            <span> Pharma way </span>
          </a>
        </nav>
      </div>
    </header>
  </div>

  <!-- Item Section -->
  <div class="item_section layout_padding2">
    <div class="container">
      <div class="item_container">
        <?php
          $select_cat = $conn->prepare("SELECT * FROM `categories`"); 
          $select_cat->execute();
          
          if($select_cat->rowCount() > 0){
            while($fetch_cat = $select_cat->fetch(PDO::FETCH_ASSOC)){
        ?>
        <div class="box">
          <div class="img-box">
            <img src="uploaded_img/<?= $fetch_cat['image']; ?>" alt="<?= $fetch_cat['category']; ?>">
          </div>
          <div class="name">
            <a href="category.php?id=<?= $fetch_cat['id']; ?>">
              <h5><?= $fetch_cat['category']; ?></h5>
            </a>
          </div>
        </div>
        <?php
            }
          } else {
            echo '<p class="empty">لا يوجد شيء مضاف حاليا</p>';
          }
        ?>
      </div>
    </div>
  </div>

  <!-- Footer Section -->
  <footer>
    <p>حقوق الطبع والنشر &copy; 2025  . جميع الحقوق محفوظة.</p>
  </footer>

  <!-- Scripts -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
</body>
</html>
