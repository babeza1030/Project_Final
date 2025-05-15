<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['username']) && isset($_GET['h_hours'])) {
    $username = $_GET['username'];
    $h_hours = intval($_GET['h_hours']);

    // ตรวจสอบว่ามีข้อมูลก่อน
    $check = $conn->prepare("SELECT id FROM new_user_activities WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();
    if ($check->num_rows == 0) {
        echo "ไม่พบ username นี้ในฐานข้อมูล";
        exit();
    }
    $check->close();

    // อัปเดตคะแนน
    $query = "UPDATE new_user_activities SET hours = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $h_hours, $username);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "บันทึกคะแนนสำเร็จ";
        } else {
            echo "ไม่มีการเปลี่ยนแปลงคะแนน (อาจเป็นค่าซ้ำ)";
        }
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ไม่มีการระบุ username หรือคะแนน";
    exit();
}
?>
<a href="admin_Check_document_status.php">← กลับ</a>
