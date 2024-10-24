<?php
// Load data from CSV
$products = [];
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
            'product_link' => $data[8],
            'offer_link' => $data[8]
        ];
    }
    fclose($handle);
}

// Load image data from shop_info.csv
$product_images = [];
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
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านบ้อง Weedsza 24Hr</title>
    <meta name="description" content="ร้านบ้อง Weedsza 24hr จำหน่ายบ้องกัญชาและผลิตภัณฑ์ต่างๆ บริการตลอด 24 ชั่วโมง">
    <link rel="stylesheet" href="styles.css">
    <style>
        @font-face {
            font-family: 'Boontook';
            src: url('fonts/NotoSerit.ttf') format('truetype');
        }
        body {
            font-family: 'Boontook', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #181818;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #dce35b;
        }
        header h1 {
            color: #dce35b;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .flip-card_i {
            perspective: 1000px;
            width: 250px; /* Set a fixed width */
            height: 360px; /* Set a fixed height */
        }
        .flip-card-inner_i {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        .flip-card-front_i, .flip-card-back_i {
            position: absolute;
            backface-visibility: hidden;
            border: 1px solid #dce35b;
            border-radius: 10px;
            overflow: hidden;
            background-color: #292929;
            width: 100%;
            height: 100%;
        }
        .flip-card-back_i {
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        .flip-card-inner_i:hover {
            transform: rotateY(180deg);
        }
        .product-card img {
            width: 10%;
            height: 20px;
            object-fit: cover;
        }
        .product-details {
            padding: 0.1px;
            text-align: center;
        }
        .product-details h2 {
            font-size: 0.01em;
            margin: 0;
            color: #dce35b;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #dce35b;
            color: #1c1c1c;
            border-radius: 50px;
            text-decoration: none;
            margin: 1px;
            transition: background-color 0.3s, transform 0.3s;
        }
        .button:hover {
            background-color: #45b649;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <header>
        <h1>ร้านบ้อง Weedsza 24Hr. [พร้อมส่ง]</h1>
    </header>
    <div class="grid-container">
        <?php foreach ($products as $product): ?>
            <div class="flip-card_i">
                <div class="flip-card-inner_i">
                    <div class="flip-card-front_i">
                        <?php
                        // Find matching image in shop_info.csv
                        $image_url = 'photo.jpg'; // Default fallback image
                        foreach ($product_images as $img_data) {
                            if ($img_data['product_name'] == $product['name']) {
                                $image_url = $img_data['image_url'];
                                break;
                            }
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-details">
                            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                        </div>
                    </div>
                    <div class="flip-card-back_i">
                        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                        <p>ราคา: <?php echo htmlspecialchars($product['price']); ?></p>
                        <p>ขายแล้ว: <?php echo htmlspecialchars($product['sold']); ?></p>
                        <p>ร้าน: <?php echo htmlspecialchars($product['shop_name']); ?></p>
                        <a href="<?php echo htmlspecialchars($product['product_link']); ?>" class="button">ดูรายละเอียด</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
