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

            <form action="user_page2_form4.php" method="post">

                <div class="question-section">
                    <h3>ข้อมูลสถานภาพครอบครัว</h3>
                    <p>คำจำกัดความสถานภาพ

                        การกรอกสถานภาพครอบครัว มีผลต่อการพิจารณาให้กู้ยืม กรุณากรอกข้อมูลตามความเป็นจริง
                        ตามสถานภาพครอบครัวที่เป็นอยู่ในปัจจุบัน ณ วันกรอกข้อมูล
                        -นักศึกษาอยู่ในความดูแลของบิดา และมารดา คือ บิดามารดาอยู่ร่วมกันฉันท์สามีภรรยา
                        ไม่ว่าจะได้ทำการสมรสกันถูกต้องตามกฎหมายหรือไม่ก็ตาม
                        - กรณีนักศึกษาอยู่กับคนใดคนหนึ่ง
                        ความหมายของสถานะ
                        1. แยกทางกัน คือ บิดามารดาไม่ได้อยู่ร่วมกันฉันท์สามีภรรยาแล้ว แต่ยังไม่ได้หย่ากันตามกฎหมาย
                        รวมทั้งผู้ที่ไม่ได้สมรสอย่างถูกต้องตามกฎหมาย แต่ไม่ได้อยู่ร่วมกันฉันท์สามีภรรยาแล้ว
                        ​2. หย่าร้าง คือ สามีภรรยาที่จดทะเบียนหย่าต่อนายทะเบียนถือว่าถูกต้องตามกฎหมาย
                        เพื่อให้ความเป็นสามีภรรยา สิ้นสุดลง
                        3. เสียชีวิต คือ หยุดหายใจ หัวใจหยุดเต้น สมองหยุดทำงานโดยสิ้นเชิง
                        4. ไม่ส่งเสียเลี้ยงดู คือ ไม่อุปการะ ส่งเสียในเรื่องค่าใช้จ่าย และไม่ดูแลนักศึกษา
                        5. หายสาบสูญ เรียกบุคคลซึ่งได้ไปจากภูมิลำเนา หรือถิ่นที่อยู่
                        และไม่มีใครรู้แน่ว่าบุคคลนั้นยังมีชีวิตอยู่หรือไม่ ตลอดระยะเวลา ๕ ปี
                        และศาลมีคำสั่งให้เป็นคนสาบสูญว่า คนสาบสูญ</p>

                    <div class="content-section">
                        <p><b>นักศึกษาอยู่ในความดูแลของใคร *</b></p>
                        <div class="form-group">
                            <input type="radio" id="both-parents" name="guardian" value="both-parents">
                            <label for="both-parents">บิดาและมารดา</label><br>
                            <input type="radio" id="father-only" name="guardian" value="father-only">
                            <label for="father-only">บิดาคนเดียว</label><br>
                            <input type="radio" id="mother-only" name="guardian" value="mother-only">
                            <label for="mother-only">มารดาคนเดียว</label><br>
                            <input type="radio" id="guardian" name="guardian" value="guardian">
                            <label for="guardian">ผู้ปกครอง (เฉพาะกรณีที่ไม่มีทั้งบิดาและมารดา)</label>
                        </div>
                    </div>
                </div>


                <button type="submit"><a href="user_page2_form4.php"></a>ตกลง</button>
                <button type="button" style="float: right;">ล้างแบบฟอร์ม</button>
            </form>
        </div>
    </div>

</body>

</html>