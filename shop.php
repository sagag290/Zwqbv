<?php
// โหลดข้อมูลจาก CSV
$products = [];
$product_images = []; // กำหนดค่าตัวแปร product_images ให้เริ่มต้นเป็นอาเรย์ว่าง
$search_query = ''; // กำหนดค่าตัวแปร search_query ให้เริ่มต้นเป็นค่าว่าง

if (($handle = fopen("shop.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle)) !== FALSE) {
        $products[] = [
            'product_id' => $data[0],
            'name' => $data[1],
            'price' => $data[2],
            'sold' => $data[3],
            'shop_name' => $data[4],
            'commission_rate' => $data[5],
            'commission' => $data[6],
            'product_link' => $data[7],
            'offer_link' => $data[8]
        ];
    }
    fclose($handle);
}

// ฟังก์ชันตรวจสอบ URL ของรูปภาพ
function check_image_url($url) {
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}

// โหลดข้อมูลรูปภาพจาก shop_info.csv
if (($handle = fopen("shop_info.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle)) !== FALSE) {
        $product_images[] = [
            'affiliate_link' => $data[0],
            'discount_badge' => $data[1],
            'off_label' => $data[2],
            'image_url' => $data[3],
            'product_name' => $data[4],
            'price' => $data[5],
            'sold_count' => $data[6],
            'commission_rate' => $data[7]
        ];
    }
    fclose($handle);
}

// กรองสินค้าตามคำค้นหา
$filtered_products = $products;

if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $filtered_products = array_filter($products, function($product) use ($search_query) {
        return stripos($product['name'], $search_query) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="th">
    <script type="text/javascript" src="//counter.websiteout.com/js/30/0/0/0"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านบ้อง Weedsza 24hr - บ้องกัญชาและผลิตภัณฑ์ 24 ชั่วโมง</title>
    <meta name="description" content="ร้านบ้อง Weedsza 24hr จำหน่ายบ้องกัญชา ผลิตภัณฑ์เกี่ยวกับกัญชา น้ำมันกัญชา และอุปกรณ์ต่างๆ บริการตลอด 24 ชั่วโมง">
    <meta name="keywords" content="บ้องกัญชา, ร้านขายบ้อง, ผลิตภัณฑ์กัญชา, น้ำมันกัญชา, กัญชา 24 ชั่วโมง, ร้านบ้องใกล้ฉัน, อุปกรณ์กัญชา">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212; /* สีพื้นหลัง */
            color: #e0e0e0; /* สีตัวอักษร */
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        header {
            background-color: #1e1e1e;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #e0e0e0;
            animation: slideIn 0.5s ease-out forwards;
        }
        header h1 {
            margin: 0;
            color: #00e676; /* สีตัวหนังสือ */
            font-size: 2.5em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        header form {
            margin-top: 10px;
        }
        input[type="text"] {
            padding: 10px;
            width: 200px;
            border-radius: 20px;
            border: 1px solid #00e676; /* สีกรอบ */
            margin-right: 5px;
            background-color: #2c2c2c; /* สีพื้นหลังของ input */
            color: #e0e0e0; /* สีตัวอักษร */
        }
        button[type="submit"] {
            background-color: #00e676; /* สีปุ่ม */
            color: #ffffff; /* สีตัวอักษรในปุ่ม */
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        button[type="submit"]:hover {
            background-color: #1de9b6; /* สีเมื่อ hover */
            transform: scale(1.05);
        }
        .magic-slider {
            display: flex;
            overflow: hidden;
            width: 100%;
            height: 300px; /* กำหนดความสูงของ slider */
            position: relative;
            margin: 20px 0;
        }
        .magic-slider img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .magic-slider img.active {
            opacity: 1;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            animation: fadeIn 0.5s ease-out forwards;
        }
        .product-card {
            background-color: #424242; /* สีพื้นของการ์ด */
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #00e676; /* สีกรอบของการ์ด */
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
        }
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            transition: opacity 0.3s;
        }
        .product-card img:hover {
            opacity: 0.8;
        }
        .product-details {
            padding: 15px;
            text-align: center;
        }
        .product-details h2 {
            font-size: 1.2em;
            margin: 0;
            color: #00e676; /* สีชื่อสินค้า */
        }
        .product-details p {
            margin: 5px 0;
            color: #e0e0e0; /* สีรายละเอียดสินค้า */
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #00e676; /* สีปุ่มซื้อ */
            color: #ffffff;
            border-radius: 50px;
            text-decoration: none;
            margin: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .button:hover {
            background-color: #1de9b6; /* สีเมื่อ hover ปุ่ม */
            transform: scale(1.05);
        }

        /* Animation Styles */
        @keyframes slideIn {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <header>
        <h1>ร้านบ้อง Weedsza 24Hr.</h1>
        <form method="GET" action="">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="ค้นหาสินค้า">
            <button type="submit">ค้นหา</button>
        </form>
    </header>
    <script async src="https://js.wpadmngr.com/static/adManager.js" data-admpid="143221"></script>
<div data-banner-id="1419867"></div>
<div data-banner-id="1419868"></div>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8691330112192774"
 crossorigin="anonymous"></script>
<script type="text/javascript">
    aclib.runAutoTag({
        zoneId: 'pl810gi7rb',
    });
</script>
<script type="text/javascript">
    aclib.runAutoTag({
        zoneId: 'i2r9hge158',
    });
</script>
    <div class="magic-slider">
        <?php foreach ($product_images as $index => $img_data): ?>
            <img src="<?php echo $img_data['image_url']; ?>" alt="<?php echo $img_data['product_name']; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>">
        <?php endforeach; ?>
    </div>

    <div class="grid-container">
        <?php foreach ($filtered_products as $product): ?>
            <div class="product-card">
                <?php
                // ค้นหารูปภาพที่ตรงกันใน shop_info.csv
                $image_url = 'photo.jpg'; // ค่าเริ่มต้นสำหรับ fallback image
                foreach ($product_images as $img_data) {
                    if ($img_data['product_name'] == $product['name']) {
                        $image_url = $img_data['image_url'];
                        break;
                    }
                }
                ?>
                <?php if (check_image_url($image_url)): ?>
                    <img src="<?php echo $image_url; ?>" alt="<?php echo $product['name']; ?>">
                <?php else: ?>
                    <img src="photo.jpg" alt="Fallback Image">
                <?php endif; ?>
                <div class="product-details">
                    <h2><?php echo $product['name']; ?></h2>
                    <p>ราคา: <?php echo $product['price']; ?> บาท</p>
                    <p>ขายแล้ว: <?php echo $product['sold']; ?> ชิ้น</p>
                    <a href="<?php echo $product['offer_link']; ?>" class="button">ซื้อเลย</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Magic Slider Animation
        const images = document.querySelectorAll('.magic-slider img');
        let currentIndex = 0;

        function showNextImage() {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        }

        setInterval(showNextImage, 3000); // เปลี่ยนรูปทุก 3 วินาที
    </script>
</body>
</html>
