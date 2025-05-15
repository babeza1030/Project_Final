<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['username']) && isset($_GET['h_hours'])) {
    $student_code = $_GET['username']; // ใช้ username ที่รับมาเป็น student_code
    $h_hours = intval($_GET['h_hours']);

    // ตรวจสอบว่ามีข้อมูลก่อน
    $check = $conn->prepare("SELECT id FROM new_user_activities WHERE student_code = ?");
    $check->bind_param("s", $student_code);
    $check->execute();
    $check->store_result();
    if ($check->num_rows == 0) {
        echo "ไม่พบ student_code นี้ในฐานข้อมูล";
        exit();
    }
    $check->close();

    // อัปเดตคะแนน
    $query = "UPDATE new_user_activities SET hours = ? WHERE student_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $h_hours, $student_code);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // อัปเดตสถานะเป็น checked
            $update_status = $conn->prepare("UPDATE new_user_activities SET status = 'checked' WHERE student_code = ?");
            $update_status->bind_param("s", $student_code);
            $update_status->execute();
            $update_status->close();

            echo "บันทึกคะแนนสำเร็จและเปลี่ยนสถานะเป็นตรวจแล้ว";
        } else {
            echo "ไม่มีการเปลี่ยนแปลงคะแนน (อาจเป็นค่าซ้ำ)";
        }
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ไม่มีการระบุ student_code หรือคะแนน";
    exit();
}
?>
<a href="admin_Check_document_status.php?year=2025&terms=1&username=&status=unchecked">← กลับ</a>
