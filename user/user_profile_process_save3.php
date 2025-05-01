<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = intval($_POST['student_id']);
    $father_name = htmlspecialchars($_POST['father_name']);
    $father_last_name = htmlspecialchars($_POST['father_last_name']);
    $father_id = htmlspecialchars($_POST['father_id']);
    $father_address = htmlspecialchars($_POST['father_address']);
    $father_occupation = htmlspecialchars($_POST['father_occupation']);
    $father_income = htmlspecialchars($_POST['father_income']);
    $father_phone_number = htmlspecialchars($_POST['father_phone_number']);
    $mother_name = htmlspecialchars($_POST['mother_name']);
    $mother_last_name = htmlspecialchars($_POST['mother_last_name']);
    $mother_id = htmlspecialchars($_POST['mother_id']);
    $mother_address = htmlspecialchars($_POST['mother_address']);
    $mother_occupation = htmlspecialchars($_POST['mother_occupation']);
    $mother_income = htmlspecialchars($_POST['mother_income']);
    $mother_phone_number = htmlspecialchars($_POST['mother_phone_number']);
    $family_status = htmlspecialchars($_POST['family_status']);

    // ตรวจสอบว่าค่า father_id และ mother_id มีอยู่ในตาราง father และ mother
    $check_father = $conn->prepare("SELECT COUNT(*) FROM father WHERE father_id = ?");
    $check_father->bind_param("s", $father_id);
    $check_father->execute();
    $check_father->bind_result($father_exists);
    $check_father->fetch();
    $check_father->close();

    $check_mother = $conn->prepare("SELECT COUNT(*) FROM mother WHERE mother_id = ?");
    $check_mother->bind_param("s", $mother_id);
    $check_mother->execute();
    $check_mother->bind_result($mother_exists);
    $check_mother->fetch();
    $check_mother->close();

    if ($father_exists == 0 || $mother_exists == 0) {
        echo "Error: Father or Mother ID does not exist in the database.";
        exit();
    }

    // Update the father table
    $sql_father = "UPDATE father SET 
                    father_name = ?, 
                    father_last_name = ?, 
                    father_id = ?, 
                    father_address = ?, 
                    father_occupation = ?, 
                    father_income = ?, 
                    father_phone_number = ? 
                   WHERE father_id = (SELECT father_id FROM student WHERE student_id = ?)";
    $stmt_father = $conn->prepare($sql_father);
    $stmt_father->bind_param(
        "sssssssi",
        $father_name,
        $father_last_name,
        $father_id,
        $father_address,
        $father_occupation,
        $father_income,
        $father_phone_number,
        $student_id
    );

    // Update the mother table
    $sql_mother = "UPDATE mother SET 
                    mother_name = ?, 
                    mother_last_name = ?, 
                    mother_id = ?, 
                    mother_address = ?, 
                    mother_occupation = ?, 
                    mother_income = ?, 
                    mother_phone_number = ? 
                   WHERE mother_id = (SELECT mother_id FROM student WHERE student_id = ?)";
    $stmt_mother = $conn->prepare($sql_mother);
    $stmt_mother->bind_param(
        "sssssssi",
        $mother_name,
        $mother_last_name,
        $mother_id,
        $mother_address,
        $mother_occupation,
        $mother_income,
        $mother_phone_number,
        $student_id
    );

    // Update the student table
    $sql_student = "UPDATE student SET 
                    family_status = ?, 
                    father_id = ?, 
                    mother_id = ? 
                    WHERE student_id = ?";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("sssi", $family_status, $father_id, $mother_id, $student_id);

    // Execute the statements
    if ($stmt_father->execute() && $stmt_mother->execute() && $stmt_student->execute()) {
        header("Location: user_profile.php?success=1");
    } else {
        echo "Error: " . $stmt_father->error . " " . $stmt_mother->error . " " . $stmt_student->error;
    }

    $stmt_father->close();
    $stmt_mother->close();
    $stmt_student->close();
    $conn->close();
} else {
    header("Location: user_profile.php");
    exit();
}
