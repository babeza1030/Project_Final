<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// รับ ID เอกสารจาก URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // ดึงข้อมูลเอกสารจากฐานข้อมูล
    $query = "SELECT * FROM documents WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $document = $result->fetch_assoc();

    if (!$document) {
        echo "ไม่พบเอกสารที่ระบุ";
        exit();
    }
} else {
    echo "ไม่มีการระบุ ID เอกสาร";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจเอกสาร</title>
</head>

<body>
    <h2>ตรวจเอกสาร</h2>
    <p>ชื่อเอกสาร: <?= htmlspecialchars($document['document_name']) ?></p>
    <p>วันที่อัปโหลด: <?= htmlspecialchars($document['upload_date']) ?></p>
    <p>สถานะ: <?= htmlspecialchars($document['status']) ?></p>
    <a href="admin_Check_document_status.php">กลับ</a>
</body>

</html>