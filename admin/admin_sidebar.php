<?php

// เชื่อมต่อกับฐานข้อมูล


include '/xampp/htdocs/Project_Final/server.php';

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: #ffffff;
        color: #333;
        padding-top: 20px;
        border-right: 2px solid #ddd;
    }

    .sidebar img {
        display: block;
        width: 80%;
        margin: 0 auto 10px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        padding: 12px 20px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .sidebar ul li a {
        color: #333;
        text-decoration: none;
        display: flex;
        align-items: center;
        font-size: 16px;
        transition: 0.3s;
    }

    .sidebar ul li a i {
        margin-right: 10px;
        font-size: 18px;
        color: #F17629;
    }

    .sidebar ul li a:hover {
        background: #f5f5f5;
        padding-left: 10px;
        border-radius: 5px;
    }
</style>


<!-- Sidebar -->
<div class="sidebar">
    <img src="../static/img/logo.png" alt="Kasem Bundit University">
    <ul>
        <li><a href="admin_dashboard.php"><i class="bi bi-house"></i> หน้าหลัก (Dashboard)</a></li>
        <li><a href="admin_edit_student.php"><i class="bi bi-person"></i> ข้อมูลนักศึกษา</a></li>
        <li><a href="admin_edit_teacher.php"><i class="bi bi-briefcase"></i> ข้อมูลอาจารย์</a></li>
        <li><a href="admin_Check_document_status.php?year=2025&terms=1&username=&status=unchecked"><i class="bi bi-file-text"></i> ตรวจสอบเอกสารจิตอาสา</a></li>
        <li><a href="admin_report_1.php"><i class="bi bi-bar-chart"></i> ตารางสรุปผู้กู้ กยศ.</a></li>
        <li><a href="admin_report_2.php"><i class="bi bi-clipboard-data"></i> ตารางรายงานสรุป จิตอาสา</a></li>
        <li><a href="admin_add_year.php"><i class="bi bi-clipboard-data"></i>เพิ่มปีการศึกษา</a></li>
        <li><a href="adminlogout.php" class="logout-btn"><i class="bi bi-box-arrow-right"></i> ออกจากระบบ</a></li>
    </ul>
</div>