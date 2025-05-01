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
    $address = $conn->real_escape_string($_POST['address']); // ป้องกัน SQL Injection

    $sql = "UPDATE student SET 
                s_faculty = $s_faculty, 
                s_department = $s_department, 
                address = '$address' 
            WHERE student_id = $student_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: user_profile.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>