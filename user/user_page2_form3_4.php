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

        <form action="user_page2_form3_5.php" method="post">
            <h3>ผู้ปกครอง (เฉพาะกรณีไม่มีทั้งบิดาและมารดาดูแล)</h3>
            <p>นักศึกษาไม่มีทั้งบิดา และมารดา อยู่ในความดูแลของผู้ปกครอง หรือบิดาและมารดาไม่ได้ประกอบอาชีพ กรุณาให้รายละเอียดเกี่ยวกับอาชีพของผู้ปกครอง<br>
                คำจำกัดความการประกอบอาชีพ<br>
                1. อาชีพอิสระ คือ การประกอบอาชีพที่มีค่าตอบแทนที่แบบไม่มีสลิปเงินเดือน หรือหนังสือรับรองเงินเดือน เช่น เกษตรกรประมง ค้าขาย รับจ้างทั่วไป ธุรกิจส่วนตัว ฯลฯ<br>
                ​2. อาชีพที่มีเงินเดือนประจำ คือ การประกอบอาชีพที่ได้รับค่าตอบแทนเป็นรายครึ่งเดือน หรือรายเดือน (มีสลิปเงินเดือน หรือหนังสือรับรองเงินเดือน) เช่น พนักงานบริษัท รับราชการ รัฐวิสาหกิจ ฯลฯ</p>

            <div class="question-section">
                <h3>บิดามีสถานะอย่างไร</h3>
                <p>คำจำกัดความสถานภาพ<br>
                    การกรอกสถานภาพครอบครัว มีผลต่อการพิจารณาให้กู้ยืม กรุณากรอกข้อมูลตามความเป็นจริง ตามสถานภาพครอบครัวที่เป็นอยู่ในปัจจุบัน ณ วันกรอกข้อมูล<br>
                    -นักศึกษาอยู่ในความดูแลของบิดา และมารดา คือ บิดามารดาอยู่ร่วมกันฉันท์สามีภรรยา ไม่ว่าจะได้ทำการสมรสกันถูกต้องตามกฎหมายหรือไม่ก็ตาม<br>
                    - กรณีนักศึกษาอยู่กับคนใดคนหนึ่ง<br>
                    ความหมายของสถานะ<br>
                    1. แยกทางกัน คือ บิดามารดาไม่ได้อยู่ร่วมกันฉันท์สามีภรรยาแล้ว แต่ยังไม่ได้หย่ากันตามกฎหมาย รวมทั้งผู้ที่ไม่ได้สมรสอย่างถูกต้องตามกฎหมาย แต่ไม่ได้อยู่ร่วมกันฉันท์สามีภรรยาแล้ว<br>
                    ​2. หย่าร้าง คือ สามีภรรยาที่จดทะเบียนหย่าต่อนายทะเบียนถือว่าถูกต้องตามกฎหมาย เพื่อให้ความเป็นสามีภรรยา สิ้นสุดลง<br>
                    3. เสียชีวิต คือ หยุดหายใจ หัวใจหยุดเต้น สมองหยุดทำงานโดยสิ้นเชิง<br>
                    4. ไม่ส่งเสียเลี้ยงดู คือ ไม่อุปการะ ส่งเสียในเรื่องค่าใช้จ่าย และไม่ดูแลนักศึกษา<br>
                    5. หายสาบสูญ เรียกบุคคลซึ่งได้ไปจากภูมิลำเนา หรือถิ่นที่อยู่ และไม่มีใครรู้แน่ว่าบุคคลนั้นยังมีชีวิตอยู่หรือไม่ ตลอดระยะเวลา ๕ ปี และศาลมีคำสั่งให้เป็นคนสาบสูญว่า คนสาบสูญ</p>
                <div class="form-group">
                    <label for="father_status">เลือกสถานภาพของบิดา:</label>
                    <select id="father_status" name="father_status" required>
                        <option value="" disabled selected>-- เลือกสถานภาพ --</option>
                        <option value="separated">บิดาแยกทางกันกับมารดา หากเลือกข้อนี้กรุณาดาวน์โหลดหนังสือรับรองสถานภาพ</option>
                        <option value="divorced">บิดาหย่าร้างกับมารดา หากเลือกข้อนี้กรุณาแนบหลักฐาน สำเนาใบหย่าที่ออกจากทางราชการ</option>
                        <option value="deceased">บิดาเสียชีวิต หากเลือกข้อนี้กรุณาแนบหลักฐาน สำเนาใบมรณบัตร หรือหลักฐานอื่น ๆ จากทางราชการที่แสดงว่าเสียชีวิต</option>
                        <option value="not_supporting">บิดาไม่ส่งเสียเลี้ยงดู หากเลือกข้อนี้กรุณาดาวน์โหลดหนังสือรับรองสถานภาพ</option>
                        <option value="missing">บิดาหายสาบสูญ หากเลือกข้อนี้กรุณาแนบหลักฐานจากทางราชการที่เป็นบุคคลสาบสูญ</option>
                        <option value="unemployed">บิดาไม่ประกอบอาชีพ หากเลือกข้อนี้ให้แนบหนังสือรับรองรายได้</option>
                    </select>
                </div>
            </div>

            <div class="question-section">
                <h3>มารดามีสถานะอย่างไร</h3>
                <p>คำจำกัดความสถานภาพ<br>
                    การกรอกสถานภาพครอบครัว มีผลต่อการพิจารณาให้กู้ยืม กรุณากรอกข้อมูลตามความเป็นจริง ตามสถานภาพครอบครัวที่เป็นอยู่ในปัจจุบัน ณ วันกรอกข้อมูล<br>
                    -นักศึกษาอยู่ในความดูแลของบิดา และมารดา คือ บิดามารดาอยู่ร่วมกันฉันท์สามีภรรยา ไม่ว่าจะได้ทำการสมรสกันถูกต้องตามกฎหมายหรือไม่ก็ตาม<br>
                    - กรณีนักศึกษาอยู่กับคนใดคนหนึ่ง<br>
                    ความหมายของสถานะ<br>
                    1. แยกทางกัน คือ บิดามารดาไม่ได้อยู่ร่วมกันฉันท์สามีภรรยาแล้ว แต่ยังไม่ได้หย่ากันตามกฎหมาย รวมทั้งผู้ที่ไม่ได้สมรสอย่างถูกต้องตามกฎหมาย แต่ไม่ได้อยู่ร่วมกันฉันท์สามีภรรยาแล้ว<br>
                    ​2. หย่าร้าง คือ สามีภรรยาที่จดทะเบียนหย่าต่อนายทะเบียนถือว่าถูกต้องตามกฎหมาย เพื่อให้ความเป็นสามีภรรยา สิ้นสุดลง<br>
                    3. เสียชีวิต คือ หยุดหายใจ หัวใจหยุดเต้น สมองหยุดทำงานโดยสิ้นเชิง <br>
                    4. ไม่ส่งเสียเลี้ยงดู คือ ไม่อุปการะ ส่งเสียในเรื่องค่าใช้จ่าย และไม่ดูแลนักศึกษา<br>
                    5. หายสาบสูญ เรียกบุคคลซึ่งได้ไปจากภูมิลำเนา หรือถิ่นที่อยู่ และไม่มีใครรู้แน่ว่าบุคคลนั้นยังมีชีวิตอยู่หรือไม่ ตลอดระยะเวลา ๕ ปี และศาลมีคำสั่งให้เป็นคนสาบสูญว่า คนสาบสูญ</p>
                <div class="form-group">
                    <label for="mother_status">เลือกสถานภาพของมารดา:</label>
                    <select id="mother_status" name="mother_status" required>
                        <option value="" disabled selected>-- เลือกสถานภาพ --</option>
                        <option value="separated">มารดาแยกทางกับบิดา หากเลือกข้อนี้กรุณาดาวน์โหลดหนังสือรับรองสถานภาพ</option>
                        <option value="divorced">มารดาหย่าร้างกับบิดา หากเลือกข้อนี้กรุณาแนบหลักฐาน สำเนาใบหย่าที่ออกจากทางราชการ</option>
                        <option value="deceased">มารดาเสียชีวิต หากเลือกข้อนี้กรุณาแนบหลักฐาน สำเนาใบมรณบัตร หรือหลักฐานอื่น ๆ จากทางราชการที่แสดงว่าเสียชีวิต</option>
                        <option value="not_supporting">มารดาไม่ส่งเสียเลี้ยงดู หากเลือกข้อนี้กรุณาดาวน์โหลดหนังสือรับรองสถานภาพ</option>
                    </select>
                </div>
            </div>

            <div class="question-section">
                <h3>ผู้ปกครองประกอบอาชีพ</h3>
                <div class="form-group">
                    <label for="parent_occupation">เลือกอาชีพของผู้ปกครอง:</label>
                    <select id="parent_occupation" name="parent_occupation" required>
                        <option value="" disabled selected>-- เลือกอาชีพ --</option>
                        <option value="free">อาชีพอิสระ หากท่านเลือกข้อนี้ กรุณาดาวน์โหลดหนังสือรับรองรายได้ กยศ. 102</option>
                        <option value="salaried">อาชีพที่มีเงินเดือนประจำ หากท่านเลือกข้อนี้ กรุณาแนบหนังสือรับรองเงินเดือน หรือสลิปเงินเดือนที่ออกจากหน่วยงานที่ผู้ปกครองปฏิบัติงานอยู่</option>
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