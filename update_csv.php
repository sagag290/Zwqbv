<?php
$api_url = "https://map.gomaps.pro/apis/AlzaSyG0MLVisphDyJUTp82XjDMhD-0HiZiIh3b"; // แก้ไข URL ให้ถูกต้อง
$data = file_get_contents($api_url);

if ($data === FALSE) {
    echo "Error occurred while fetching the data from API.";
    exit;
}

// จากนั้นทำการประมวลผลข้อมูลจาก API ตามปกติ

// ส่งคำขอไปยัง Google Maps API
$response = file_get_contents($api_url);
if ($response === FALSE) {
    die('Error occurred while fetching the data from API.');
}

// แปลงข้อมูลที่ได้รับเป็น JSON
$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Invalid JSON format.');
}

// สร้างไฟล์ CSV ขึ้นมาใหม่
$csv_file = fopen("$province.csv", 'w');
if ($csv_file === FALSE) {
    die('Cannot open CSV file for writing.');
}

// เขียนข้อมูลลง CSV
foreach ($data['results'] as $shop) {
    fputcsv($csv_file, [
        $shop['name'],
        $shop['formatted_address'],
        $shop['formatted_phone_number'],
        $shop['website'],
        $shop['rating'],
        $shop['user_ratings_total'],
        $shop['reviews'][0]['text'] ?? '',
        $shop['reviews'][1]['text'] ?? '',
        $shop['reviews'][2]['text'] ?? '',
        $shop['photo_reference'] ?? 'photo.jpg',
        $shop['url']
    ]);
}

fclose($csv_file);
?>
