<?php
session_start();

// เชื่อมต่อฐานข้อมูล
include '/xampp/htdocs/Project_Final/server.php';

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง family_status_code
$sql = "SELECT status_code_id, description FROM family_status_code";
$result = $conn->query($sql);

// ตรวจสอบการส่งข้อมูลฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status_code_id = $_POST['guardian']; // รับค่า status_code_id จากฟอร์ม
    $username = $_SESSION['username']; // ใช้ username จาก session

    // ตรวจสอบว่าค่า status_code_id มีอยู่ในตาราง status หรือไม่
    $check_sql = "SELECT family_status_id FROM status WHERE family_status_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $status_code_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        die("Error: The selected status_code_id does not exist in the status table.");
    }

    // บันทึกข้อมูลลงในตาราง student
    $insert_sql = "UPDATE student SET status_code_id = ? WHERE student_id = ?";
    $stmt = $conn->prepare($insert_sql);

    // ตรวจสอบว่า prepare สำเร็จหรือไม่
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ss", $status_code_id, $username);

    if ($stmt->execute()) {
        echo "<script>alert('บันทึกข้อมูลสำเร็จ');</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');</script>";
    }

    $stmt->close();
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

            <form action="user_page2_form3_1.php" method="post">
                <div class="question-section">
                    <h3>บิดาและมารดา *</h3>
                    <div class="form-group">
                        <label for="guardian">เลือกสถานภาพครอบครัว:</label>
                        <select id="guardian" name="guardian" required>
                            <option value="" disabled selected>-- เลือกสถานภาพ --</option>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['status_code_id'] . '">' . $row['description'] . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>ไม่มีข้อมูล</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                            
            <div class="question-section">
                <h3>มารดาประกอบอาชีพ *</h3>
                <div class="form-group">
                    <label for="mother_job">เลือกอาชีพของมารดา:</label>
                    <select id="mother_job" name="mother_job" required>
                        <option value="" disabled selected>-- เลือกอาชีพ --</option>
                        <option value="mother-piisra">มารดาประกอบอาชีพอิสระ หากทำเลือกข้อนี้ กรุณาดาวน์โหลดหนังสือรับรองรายได้ กยศ. 102</option>
                        <option value="mother-money">มารดาที่มีเงินเดือนประจำ หากทำเลือกข้อนี้ กรุณาแนบหนังสือรับรองเงินเดือน หรือสลิปเงินเดือนที่ออกจากการหน่วยงานที่บิดาปฏิบัติงานอยู่</option>
                        <option value="mother-no-income">ไม่ได้ประกอบอาชีพ หากทำเลือกข้อนี้ กรุณาดาวน์โหลดหนังสือรับรองรายได้ กยศ.102</option>
                    </select>
                </div>
            </div>
            <div class="question-section">
                <h3>บิดาประกอบอาชีพ *</h3>
                <div class="form-group">
                    <label for="mother_job">เลือกอาชีพของบิดา:</label>
                    <select id="mother_job" name="mother_job" required>
                        <option value="" disabled selected>-- เลือกอาชีพ --</option>
                        <option value="mother-piisra">บิดาประกอบอาชีพอิสระ หากทำเลือกข้อนี้ กรุณาดาวน์โหลดหนังสือรับรองรายได้ กยศ. 102</option>
                        <option value="mother-money">บิดาที่มีเงินเดือนประจำ หากทำเลือกข้อนี้ กรุณาแนบหนังสือรับรองเงินเดือน หรือสลิปเงินเดือนที่ออกจากการหน่วยงานที่บิดาปฏิบัติงานอยู่</option>
                        <option value="mother-no-income">ไม่ได้ประกอบอาชีพ หากทำเลือกข้อนี้ กรุณาดาวน์โหลดหนังสือรับรองรายได้ กยศ.102</option>
                    </select>
                </div>
            </div>

            <div class="question-section">
                <h3>เอกสารที่ต้องเตรียมเพิ่มเติมสำหรับนักศึกษาที่อยู่ในความดูแลของบิดาและมารดา แยกตามอาชีพของบิดามารดา คือ</h3>
                <div class="form-group">
                    <label for="additional_docs">เลือกเอกสารที่ต้องเตรียม:</label>
                    <select id="additional_docs" name="additional_docs" required>
                        <option value="" disabled selected>-- เลือกเอกสาร --</option>
                        <option value="doc1">บิดาและมารดาประกอบอาชีพอิสระ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.1</option>
                        <option value="doc2">บิดาประกอบอาชีพอิสระ มารดามีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.2</option>
                        <option value="doc3">บิดาประกอบอาชีพอิสระ มารดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.3</option>
                        <option value="doc4">มารดาประกอบอาชีพอิสระ บิดามีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.4</option>
                        <option value="doc5">บิดาและมารดาประกอบอาชีพมีเงินเดือนประจำ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.5</option>
                        <option value="doc6">บิดามีเงินเดือนประจำ มารดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.6</option>
                        <option value="doc7">มารดาประกอบอาชีพอิสระ บิดาไม่ประกอบอาชีพ จดจำ ว่าสถานภาพครอบครัวอยู่ข้อ 1.7</option>
                        <option value="doc8">บิดาและมารดาไม่ประกอบอาชีพ กรุณาไปข้อต่อไปเพื่อให้ข้อมูลผู้ปกครอง</option>
                    </select>
                </div>
            </div>
                <button type="submit">ตกลง</button>
                <button type="button" style="float: right;">ล้างแบบฟอร์ม</button>
            </form>
        </div>
    </div>

</body>

</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>