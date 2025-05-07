<?php
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดาวน์โหลดแบบฟอร์มขอกู้ยืมปี 2568</title>

    <link rel="stylesheet" href="../static/css/style.css">
    <link rel="stylesheet" href="../static/css/bootstrap.css">

    <style>
        .content {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            color: #F17629;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 10px;
        }

        .content form {
            margin-bottom: 20px;
        }

        .content label {
            display: block;
            margin-bottom: 5px;
        }

        .content select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .content button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #F17629;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .content button:hover {
            background-color: #e06a0d;
        }

        .content .email-section {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .content .email-section p {
            margin-bottom: 5px;
        }

        .content .question-section {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .content .question-section h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .content .question-section p {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

<?php include('../user/header.php'); ?>

<div class="container">
    <div class="content">
        <h2>ดาวน์โหลดแบบฟอร์มขอกู้ยืมปี 2568</h2>
        <p>* ระบุว่าเป็นคำถามที่จำเป็น</p>

        <form action="user_page2_form5.php" method="post">


            <div class="question-section">
                <h3>ผู้ปกครองอาชีพมีเงินเดือนประจำ</h3>
                <p>เอกสารที่ต้องเตรียมเพิ่มเติมสำหรับนักศึกษาที่อยู่ในความดูแลของผู้ปกครองอาชีพมีเงินเดือนประจำ แยกตามสถานภาพครอบครัว  คือ</p>
                <div class="form-group">
                    <label for="parent_occupation">เลือกอาชีพของผู้ปกครอง:</label>
                    <select id="parent_occupation" name="parent_occupation" required>
                        <option value="" disabled selected>-- เลือกอาชีพ --</option>
                        <option value="2.6">บิดาไม่ประกอบอาชีพ มารดาแยกทาง/หย่าร้าง/สาบสูญ/ไม่ส่งเสียเลี้ยงดู (ข้อ 2.6)</option>
                        <option value="2.8">บิดาไม่ประกอบอาชีพ มารดาเสียชีวิต (ข้อ 2.8)</option>
                        <option value="3.6">มารดาไม่ประกอบอาชีพ บิดาแยกทาง/หย่าร้าง/สาบสูญ/ไม่ส่งเสียเลี้ยงดู (ข้อ 3.6)</option>
                        <option value="3.8">มารดาไม่ประกอบอาชีพ บิดาเสียชีวิต (ข้อ 3.8)</option>
                        <option value="4.2">บิดามารดาแยกทาง/หย่าร้าง/สาบสูญ/ไม่ส่งเสียเลี้ยงดู (ข้อ 4.2)</option>
                        <option value="4.4">มารดาเสียชีวิต บิดาแยกทาง/หย่าร้าง/สาบสูญ/ไม่ส่งเสียเลี้ยงดู (ข้อ 4.4)</option>
                        <option value="4.6">บิดาเสียชีวิต มารดาแยกทาง/หย่าร้าง/สาบสูญ/ไม่ส่งเสียเลี้ยงดู (ข้อ 4.6)</option>
                        <option value="4.8">บิดาและมารดาเสียชีวิต (ข้อ 4.8)</option>
                    </select>
                </div>
            </div>


            

            <button type="submit">ตกลง</button>
            <button type="button" onclick="document.querySelector('form').reset();" style="float: right;">ล้างแบบฟอร์ม</button>
        </form>
    </div>
</div>

</body>

</html>