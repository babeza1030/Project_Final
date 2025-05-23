<?php
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>นักศึกษาเริ่มกู้กับเกษม</title>

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

        .content h2, .content h3 {
            color: #F17629;
            margin-bottom: 20px;
        }

        .content p {
            margin-bottom: 10px;
        }

        .content ul {
            list-style-type: none;
            padding-left: 0;
        }

        .content ul li {
            margin-bottom: 10px;
        }

        .content ul li::before {
            content: "•";
            color: #F17629;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        .content a {
            color: #F17629;
            text-decoration: none;
        }

        .content a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include('../user/header.php'); ?>

<div class="container">
    <div class="content">
        <h2>นักศึกษาใหม่เริ่มกู้ปีแรก</h2>
        <p>การกู้ยืมเงินกองทุนให้กู้ยืมเพื่อการศึกษา สำหรับนักศึกษาใหม่เริ่มกู้ยืมปีแรกกับมหาวิทยาลัยเกษมบัณฑิต มีขั้นตอนการกู้ยืมดังต่อไปนี้</p>

        <h3>ภาคการศึกษาที่ 1</h3>
        <ul>
            <li><a href="https://kbu.ac.th/">สมัครเรียน</a></li>
            <li>เตรียมเอกสารการกู้ยืม</li>
            <li>ยื่นเอกสารพร้อมสัมภาษณ์</li>
            <li>
                ตรวจสอบเลขที่สัญญา (ปรับข้อมูลทุก ๆ 3 วันทำการ) หรือที่ 
                <a href="http://reg.kbu.ac.th">http://reg.kbu.ac.th</a> หากมีข้อสงสัยสอบถามได้ที่ 
                <a href="https://www.facebook.com/kasemloanstudentloan">page facebook : kasemloan studentloan</a>
                <br>ขอให้นักศึกษาจดจำเลขที่สัญญาของตนเองเพื่อตรวจสอบข้อมูลต่าง ๆ ต้องขออภัยในความไม่สะดวก เนื่องจากกฎหมาย PDPA ไม่ให้ประกาศ ชื่อ-สกุล รหัสนักศึกษา เลขบัตรประชาชน
            </li>
            <li>
                ลงทะเบียนขอรหัสผ่าน (สำหรับนักศึกษาใหม่ไม่เคยกู้ยืม)
                <ul>
                    <li><a href="https://loan.kbu.ac.th/home/sites/all/libraries/kcfinder/sites/default/files/kcfinder/files/%E0%B8%82%E0%B8%B1%E0%B9%89%E0%B8%99%E0%B8%95%E0%B8%AD%E0%B8%99%E0%B8%82%E0%B8%AD%E0%B8%A3%E0%B8%AB%E0%B8%B1%E0%B8%AA%E0%B8%9C%E0%B9%88%E0%B8%B2%E0%B8%99%20DSL%20%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%88%E0%B8%B3%E0%B8%9B%E0%B8%B5%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%A8%E0%B8%B6%E0%B8%81%E0%B8%A9%E0%B8%B2%202566.pdf">ขั้นตอนการลงทะเบียนเรียนขอรหัสผ่านช่องทางเครื่องคอมพิวเตอร์</a></li>
                    <li><a href="https://loan.kbu.ac.th/home/sites/all/libraries/kcfinder/sites/default/files/kcfinder/files/%E0%B8%82%E0%B8%B1%E0%B9%89%E0%B8%99%E0%B8%95%E0%B8%AD%E0%B8%99%E0%B8%81%E0%B8%B2%E0%B8%A3%20Download%20Application%20%E0%B8%81%E0%B8%A2%E0%B8%A8%281%29.pdf">ขั้นตอนการลงทะเบียนขอรหัสผ่านช่องทางมือถือ Application กยศ.Connect</a></li>
                </ul>
            </li>
            <li>
                <a href="https://www.youtube.com/watch?v=of_ImH4OgqE">ยื่นกู้ยืมผ่านระบบ DSL ตัวอย่างการยื่นกู้ ตัวอย่างยื่นกู้แบบ VDO</a>
                <ul>
                    <li>6.1 นักศึกษาบันทึกข้อมูลเกี่ยวกับนักศึกษา และบิดามารดา หรือ (กรณีไม่มีบิดาและมารดาบันทึกข้อมูลผู้ปกครอง)</li>
                    <li>6.2 ดาวน์โหลดพร้อมพิมพ์หนังสือยินยอมเปิดเผยข้อมูลของนักศึกษา และของบิดา และมารดา หรือผู้ปกครอง อย่างล่ะ 1 ชุด</li>
                    <li>6.3 สแกนหนังสือยินยอมเปิดเผยข้อมูลของนักศึกษาที่ลงนามเรียบร้อยแล้ว และบัตรประชาชนของนักศึกษาที่ไม่หมดอายุ เข้าระบบ DSL</li>
                    <li>6.4 สแกนหนังสือยินยอมเปิดเผยข้อมูลของบิดาและมารดาที่ลงนามเรียบร้อยแล้ว และบัตรประชาชนของบิดาและมารดาที่ไม่หมดอายุ เข้าระบบ DSL</li>
                </ul>
            </li>
            <li>รอผลการอนุมัติให้กู้ยืม มี 2 กรณี:
                <ul>
                    <li>7.1 ระบบอนุมัติให้กู้ยืม แจ้งผลทาง e-mail ของนักศึกษา และ Application กยศ. connect</li>
                    <li>7.2 ระบบให้ส่งเอกสารเพิ่มเติม แจ้งเอกสารที่ต้องนำส่งทาง e-mail ของนักศึกษา และ Application กยศ. connect</li>
                </ul>
            </li>
            <li>ระหว่างรออนุมัติให้เปิดบัญชีธนาคารกรุงไทย หรือธนาคารอิสลาม การเปิดบัญชีใช้เอกสารดังนี้:
                <ul>
                    <li>8.1 ใบอนุมัติ ได้รับจากทางกองทุนของมหาวิทยาลัย</li>
                    <li>8.2 สำเนาทะเบียนบ้านของนักศึกษา จำนวน 1 แผ่น</li>
                    <li>8.3 บัตรประชาชนตัวจริงของนักศึกษาที่ไม่หมดอายุ</li>
                    <li>8.4 สำเนาบัตรประชาชนของนักศึกษาที่ไม่หมดอายุ จำนวน 1 แผ่น</li>
                    <li>8.5 สำเนาบัตรนักศึกษา จำนวน 1 แผ่น (บางสาขาต้องใช้)</li>
                    <li>8.6 เข้าร่วม Google Classroom <a href="https://classroom.google.com/c/MTIzNDU2Nzg5MDEy?cjc=uj6c3yh">คลิกเพื่อเข้าร่วม</a> รหัสเข้าร่วม uj6c3yh</li>
                </ul>
            </li>
            <li>เมื่อได้รับอนุมัติ มีข้อความแจ้งผลการอนุมัติทาง e-mail ของนักศึกษา และ Application กยศ. connect สิ่งที่ต้องปฏิบัติ คือ เข้าระบบ DSL เพื่อดำเนินการ:
                <ul>
                    <li>9.1 บันทึกข้อมูลสัญญาพร้อมพิมพ์ ตัวอย่างข้อ 1-10</li>
                    <li>9.2 ยืนยันยอดกู้ยืม ตัวอย่างข้อ 12 - 24 (เริ่มยืนยันได้วันที่ ............................. เป็นต้นไป) หากทำผิดต้องการยกเลิก ตัวอย่างข้อ 33 - 38</li>
                    <li>9.3 พิมพ์แบบยืนยันการเบิกเงินกู้ยืม ตัวอย่างข้อ 25 - 29 (เริ่มพิมพ์แบบยืนยันได้วันที่ ........................... เป็นต้นไป)</li>
                    <li>9.4 วิธีการลงนาม:
                        <ul>
                            <li>- นักศึกษาลงนามต่อหน้าอาจารย์กองทุนเท่านั้นในวันส่งสัญญาและแบบเบิกเงินพร้อมเอกสารประกอบ</li>
                            <li>- ผู้แทนโดยชอบธรรม สามารถมาลงนามต่อหน้าอาจารย์กองทุน</li>
                            <li>- ผู้แทนโดยชอบธรรม สามารถถ่ายคลิปวีดีโอกรณีไม่สามารถมาลงนามต่อหน้าอาจารย์กองทุนได้ โดยการถ่ายคลิปมีข้อควรระวังดังนี้:
                                <ul>
                                    <li>1. ถ่ายให้เห็นบัตรประชาชนกับหน้าผู้แทนโดยชอบธรรม</li>
                                    <li>2. ถ่ายให้เห็นหัวสัญญาปีการศึกษา 2568 (แผ่นที่ 1)</li>
                                    <li>3. ถ่ายให้เห็นหน้าผู้แทนโดยชอบธรรามขณะเซ็นสัญญา ในช่องผู้แทนโดยชอบธรรม (แผ่นที่ 7)</li>
                                    <li>4. ถ่ายให้เห็นหน้าผู้แทนโดยชอบธรรมขณะเซ็นแบบเบิกเงิน ในช่องผู้แทนโดยชอบธรรม</li>
                                    <li>5. ถ่ายให้เห็นหน้าผู้แทนโดยชอบธรรมขณะเซ็นสำเนาถูกต้องในสำเนาบัตรประจำตัวประชาชนของผู้แทนโดยชอบธรรม</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>ตรวจสอบผลการนำส่งสัญญา และแบบยืนยันการเบิกเงิน พร้อมเอกสารประกอบ ตัวอย่างการเข้าเพื่อตรวจสอบ</li>
            <li>เข้าปฐมนิเทศ ...................... ณ วิทยาเขตร่มเกล้า อาคารเกษมพฤกษา ชั้น 3 ห้องเกษมสโมสร ทำแบบทดสอบ ลงทะเบียนเข้าห้องเวลา 08.45 - 09.00 น. ดูรายละเอียด</li>
            <li><a href="http://reg.kbu.ac.th">เก็บหลักฐานการกู้ยืม โดยดาวน์โหลดจาก http://reg.kbu.ac.th</a> <a href="https://loan.kbu.ac.th/home/sites/default/files/loan/files/sept%20download.pdf">ขั้นตอนการดาวน์โหลดเอกสารสำคัญการกู้ยืม</a></li>
            <li><a href="https://loan.kbu.ac.th/home/sites/default/files/files/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%9B%E0%B8%B4%E0%B8%A1%E0%B8%9E%E0%B9%8C%E0%B9%83%E0%B8%9A%E0%B9%80%E0%B8%AA%E0%B8%A3%E0%B9%87%E0%B8%88%E0%B8%A3%E0%B8%B1%E0%B8%9A%E0%B9%80%E0%B8%87%E0%B8%B4%E0%B8%99.pdf">การพิมพ์ใบเสร็จรับเงิน</a> เพื่อเก็บไว้เป็นหลักฐานในการชำระค่าเล่าเรียนด้วยการกู้ยืม</li>
            <li>ดาวน์โหลดเอกสารที่เกี่ยวข้อง</li>
        </ul>

        <h3>ภาคการศึกษาที่ 2</h3>
        <p>รายละเอียดสำหรับภาคการศึกษาที่ 2</p>

        <h3>ภาคการศึกษาที่ 3</h3>
        <p>รายละเอียดสำหรับภาคการศึกษาที่ 3</p>
    </div>
</div>

</body>
</html>