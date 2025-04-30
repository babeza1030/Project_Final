<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $s_faculty = intval($_POST['s_faculty']);
    $s_department = intval($_POST['s_department']);

    $sql = "UPDATE student SET 
                s_faculty = $s_faculty, 
                s_department = $s_department 
            WHERE student_id = $student_id";

    if ($conn->query($sql) === TRUE) {
        echo "ข้อมูลถูกบันทึกเรียบร้อยแล้ว";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>