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

        .content input[type="radio"] {
            margin-right: 5px;
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
                <h3>บิดาและมารดา *</h3>
                <div class="form-group">
                    <input type="radio" id="both-parents" name="guardian" value="both-parents">
                    <label for="both-parents">บิดาและมารดาประกอบอาชีพอิสระ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.1</label><br>
                    <input type="radio" id="father-only" name="guardian" value="father-only">
                    <label for="father-only">บิดาประกอบอาชีพอิสระ มารดามีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.2</label><br>
                    <input type="radio" id="mother-only" name="guardian" value="mother-only">
                    <label for="mother-only">บิดาประกอบอาชีพอิสระ มารดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.3</label><br>
                    <input type="radio" id="mother-salary" name="guardian" value="mother-salary">
                    <label for="mother-salary">มารดาประกอบอาชีพอิสระ บิดามีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.4</label><br>
                    <input type="radio" id="both-salary" name="guardian" value="both-salary">
                    <label for="both-salary">บิดาและมารดาประกอบอาชีพมีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.5</label><br>
                    <input type="radio" id="father-salary" name="guardian" value="father-salary">
                    <label for="father-salary">บิดามีเงินเดือนประจำ มารดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.6</label><br>
                    <input type="radio" id="mother-independent" name="guardian" value="mother-independent">
                    <label for="mother-independent">มารดาประกอบอาชีพอิสระ บิดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.7</label><br>
                    <input type="radio" id="no-job" name="guardian" value="no-job">
                    <label for="no-job">บิดาและมารดาไม่ประกอบอาชีพ กรุณาไปข้อต่อไปเพื่อให้ข้อมูลผู้ปกครอง</label><br>
                </div>
            </div>

            <div class="question-section">
                <h3>มารดาประกอบอาชีพ *</h3>
                <div class="form-group">
                    <input type="radio" id="mother-piisra" name="mother_job" value="mother-piisra">
                    <label for="mother-piisra">มารดาประกอบอาชีพอิสระ หากทำเลือกข้อนี้ กรุณาดาวน์โหลดหนังสือรับรองรายได้ กยศ. 102</label><br>
                    <input type="radio" id="mother-money" name="mother_job" value="mother-money">
                    <label for="mother-money">มารดาที่มีเงินเดือนประจำ หากทำเลือกข้อนี้ กรุณาแนบหนังสือรับรองเงินเดือน หรือสลิปเงินเดือนที่ออกจากการหน่วยงานที่บิดาปฏิบัติงานอยู่</label><br>
                    <input type="radio" id="mother-no-income" name="mother_job" value="mother-no-income">
                    <label for="mother-no-income">ไม่ได้ประกอบอาชีพ หากทำเลือกข้อนี้ กรุณาดาวน์โหลดหนังสือรับรองรายได้ กยศ.102</label><br>
                </div>
            </div>

            <div class="question-section">
                <h3>เอกสารที่ต้องเตรียมเพิ่มเติมสำหรับนักศึกษาที่อยู่ในความดูแลของบิดาและมารดา แยกตามอาชีพของบิดามารดา คือ</h3>
                <div class="form-group">
                    <input type="radio" id="doc1" name="additional_docs" value="doc1">
                    <label for="doc1">บิดาและมารดาประกอบอาชีพอิสระ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.1</label><br>
                    <input type="radio" id="doc2" name="additional_docs" value="doc2">
                    <label for="doc2">บิดาประกอบอาชีพอิสระ มารดามีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.2</label><br>
                    <input type="radio" id="doc3" name="additional_docs" value="doc3">
                    <label for="doc3">บิดาประกอบอาชีพอิสระ มารดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.3</label><br>
                    <input type="radio" id="doc4" name="additional_docs" value="doc4">
                    <label for="doc4">มารดาประกอบอาชีพอิสระ บิดามีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.4</label><br>
                    <input type="radio" id="doc5" name="additional_docs" value="doc5">
                    <label for="doc5">บิดาและมารดาประกอบอาชีพมีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.5</label><br>
                    <input type="radio" id="doc6" name="additional_docs" value="doc6">
                    <label for="doc6">บิดามีเงินเดือนประจำ มารดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.6</label><br>
                    <input type="radio" id="doc7" name="additional_docs" value="doc7">
                    <label for="doc7">มารดาประกอบอาชีพอิสระ บิดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.7</label><br>
                    <input type="radio" id="doc8" name="additional_docs" value="doc8">
                    <label for="doc8">บิดาและมารดาไม่ประกอบอาชีพ กรุณาไปข้อต่อไปเพื่อให้ข้อมูลผู้ปกครอง</label><br>
                </div>
            </div>

            <button type="submit">ตกลง</button>
            <button type="button" style="float: right;">ล้างแบบฟอร์ม</button>
        </form>
    </div>
</div>

</body>

</html>