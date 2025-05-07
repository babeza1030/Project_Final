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

        <form action="user_page2_form3_4.php" method="post">

            <div class="question-text">
                <h3>หนังสือให้ความยินยอม</h3>
                <p>การกู้ยืมทุกคนจะต้องมีหนังสือให้ความยินยอมที่เขียนด้วยลายมือตัวเองเท่านั้น
                    กรุณาดาวน์โหลดตามจำนวนดังต่อไปนี้</p>
            </div>

            <div class="question-section">
                <div class="content-section">
                    <p><b>หนังสือให้ความยินยอม *</b></p>
                    <label for="consent_form">เลือกจำนวนหนังสือให้ความยินยอม:</label>
                    <select id="consent_form" name="consent_form" required>
                        <option value="" disabled selected>-- เลือกจำนวน --</option>
                        <option value="both-parents">นักศึกษาอยู่ในความดูแลของบิดาและมารดา ดาวน์โหลด 3 แผ่น</option>
                        <option value="father-only">นักศึกษาอยู่ในความดูแลของบิดาคนเดียว ดาวน์โหลด 2 แผ่น</option>
                        <option value="mother-only">นักศึกษาอยู่ในความดูแลของมารดาคนเดียว ดาวน์โหลด 2 แผ่น</option>
                        <option value="guardian">นักศึกษาอยู่ในความดูแลของผู้ปกครอง (กรณีไม่ได้อยู่ในความดูแลของทั้งบิดาและมารดา) ดาวน์โหลด 2 แผ่น</option>
                        <option value="both-parents-guardian">นักศึกษาอยู่ในความดูแลของบิดามารดา และผู้ปกครอง ดาวน์โหลด 4 แผ่น (กรณีบิดาและมารดาไม่มีรายได้)</option>
                    </select>
                </div>
            </div>

            <div class="question-section">
                <div class="content-section">
                    <p><b>แบบฟอร์มการกู้ยืม *</b></p>
                    <p>นักศึกษาสามารถดาวน์โหลดแบบฟอร์มการกู้ยืม กยศ.101ได้หลังจากบันทึกข้อมูลและกดส่งเรียบร้อยแล้ว
                        ขอให้นำส่งหลังจากดาวน์โหลดแบบฟอร์มการกู้ยืม กยศ.101 ภายใน 7 วันทำการ</p>
                    <label for="loan_form">รับทราบ:</label>
                    <select id="loan_form" name="loan_form" required>
                        <option value="" disabled selected>-- เลือกคำตอบ --</option>
                        <option value="acknowledged">รับทราบ</option>
                    </select>
                    <p>
                        <a href="https://www.studentloan.or.th/sites/default/files/files/highlight/%E0%B8%81%E0%B8%A2%E0%B8%A8.102.pdf" target="_blank" style="color: blue; text-decoration: underline;">
                            ดาวน์โหลดหนังสือรับรองรายได้ กยศ. 102
                        </a>
                    </p>
                </div>
            </div>

            <button type="submit">ส่งแบบฟอร์ม</button>
            <button type="button" onclick="document.querySelector('form').reset();" style="float: right;">ล้างแบบฟอร์ม</button>
        </form>
    </div>
</div>

</body>

</html>