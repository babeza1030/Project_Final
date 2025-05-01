<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $student_code = $conn->real_escape_string($_POST['student_code']); // ป้องกัน SQL Injection
    $f_name = $conn->real_escape_string($_POST['f_name']); // ป้องกัน SQL Injection
    $l_name = $conn->real_escape_string($_POST['l_name']); // ป้องกัน SQL Injection
    $phone_number = $conn->real_escape_string($_POST['phone_number']); // ป้องกัน SQL Injection
    $email = $conn->real_escape_string($_POST['email']); // ป้องกัน SQL Injection
    $s_faculty = intval($_POST['s_faculty']);
    $s_department = intval($_POST['s_department']);
    $address = $conn->real_escape_string($_POST['address']); // ป้องกัน SQL Injection

    $sql = "UPDATE student SET 
                student_code = '$student_code', 
                f_name = '$f_name',
                l_name = '$l_name',
                phone_number = '$phone_number',
                email = '$email',
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