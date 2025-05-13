<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activity_id = $_POST['activity_id'];
    $hours = $_POST['hours'];

    // ตรวจสอบว่าค่าชั่วโมงอยู่ในช่วงที่กำหนด
    if ($hours >= 0) {
        $sql = "UPDATE new_user_activities SET hours = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $hours, $activity_id);

        if ($stmt->execute()) {
            echo "<script>alert('บันทึกชั่วโมงสำเร็จ!'); window.location.href='admin_Check_document_status.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกชั่วโมง!'); window.location.href='admin_Check_document_status.php';</script>";
        }
    } else {
        echo "<script>alert('ค่าชั่วโมงไม่ถูกต้อง!'); window.location.href='admin_Check_document_status.php';</script>";
    }
}
