<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

include 'components/wishlist_cart.php';

// إعداد الترقيم
$limit = 12; // المنتجات التي سيتم عرضها في الصفحة
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// استعلام للحصول على جميع المنتجات مع الترقيم
$select_products = $conn->prepare("SELECT * FROM products LIMIT $limit OFFSET $offset");
$select_products->execute();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>جميع المنتجات</title>

    <link rel="stylesheet" href="css/bootstrap.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/responsive.css" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .container {
            padding: 20px;
        }

        .heading_container {
            text-align: center;
            margin-bottom: 30px;
        }

        .price_section {
            background-color: #fff;
            padding: 30px 0;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .price_container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .box {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .box:hover {
            transform: translateY(-10px);
        }

        .img-box {
            width: 100%;
            height: 200px; /* ارتفاع مناسب للصورة */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-image {
            width: auto;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .name h6 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .detail-box h5 {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 18px;
            margin-right: 10px;
        }

        .new-price {
            color: #28a745;
            font-weight: bold;
            font-size: 20px;
        }

        .btn-warning {
            background-color: #ff7f3f;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #ff6347;
        }

        .stars {
            color: #ff7f3f;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .page-link {
            background-color: #ff7f3f;
            border: none;
            padding: 10px 15px;
            border-radius:   5px;
            margin: 0 5px;
            color: white;
            text-decoration: none;
        }

        .page-link:hover {
            background-color: #ff6347;
        }

        .page-link.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body class="sub_page">

    <header>
        Pharma way
    </header>

    <div class="container">
        <div class="heading_container">
            <h2>جميع المنتجات</h2>
        </div>

        <section class="price_section layout_padding">
            <div class="price_container">
                <?php
                if ($select_products->rowCount() > 0) {
                    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="post" style="all: unset;">
                    <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                    <input type="hidden" name="name" value="<?= $fetch_product['title']; ?>">
                    <input type="hidden" name="price" value="<?= $fetch_product['new_price']; ?>">
                    <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

                    <div class="box">
                        <div class="name">
                            <h6><?= $fetch_product['title']; ?></h6>
                        </div>
                        <div class="img-box">
                            <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="" class="product-image" />
                        </div>

                        <div class="detail-box">
                            <h5>
                                <span class="old-price">₪ <?= $fetch_product['old_price']; ?></span>
                                <span class="new-price">₪ <?= $fetch_product['new_price']; ?></span>
                            </h5>
                            <div class="stars">
                                <?php
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < $fetch_product['rating'] ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <p>التقييم: <?= $fetch_product['rating']; ?> / 5 (<?= $fetch_product['review_count']; ?> تقييم)</p>
                            <div class="flex">
                                <div class="price"><span>₪</span><?= $fetch_product['new_price']; ?><span>/-</span></div>
                                <input type="number" name="qty" class="qty" min="1" max="<?= $fetch_product['quantity']; ?>" value="1" onkeypress="if(this.value.length == 2) return false;">
                            </div>
                            <p>الكمية المتوفرة: <?= $fetch_product['quantity']; ?></p>
                            <input type="submit" value="اضف الى السلة" class="btn btn-warning shadow-0 me-1" name="add_to_cart">
                            <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>">معاينة</a>
                        </div>
                    </div>
                </form>
                <?php
                    }
                } else {
                    echo '<p class="empty">لا يوجد منتجات</p>';
                }
                ?>
            </div>
        </section>

        <!-- الترقيم -->
        <div class="pagination">
            <?php
            // استعلام للحصول على إجمالي عدد المنتجات
            $total_products = $conn->prepare("SELECT COUNT(*) FROM products");
            $total_products->execute();
            $total = $total_products->fetchColumn();
            $total_pages = ceil($total / $limit);

            // عرض الروابط للصفحات
            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($page == $i) ? 'active' : '';
                echo "<a href='?page=$i' class='page-link $active'>$i</a> ";
            }
            ?>
        </div>
    </div>

    <footer>
        <p>حقوق الطبع والنشر &copy; 2025 جميع الحقوق محفوظة.</p>
    </footer>

    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>

</body>

</html>