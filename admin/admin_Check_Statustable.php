<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Borrower Status</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ปรับพื้นหลังและฟอร์ม */
        body {
            background: #f4f7f6;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
            color: #555;
        }

        .form-control {
            border: 2px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-primary {
            background: #F17629;
            border: none;
        }

        .btn-primary:hover {
            background: #d65c1e;
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        @media (max-width: 768px) {
            .container {
                max-width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Check Borrower Status</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="selection">ID Card Number</label>
                <input type="text" class="form-control" id="selection" name="selection">
            </div>
            <!-- <div class="form-group">
                <label for="student_code">Student Code</label>
                <input type="text" class="form-control" id="student_code" name="student_code">
            </div> -->
            <button type="submit" class="btn btn-primary">Check Status</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selection = $_POST['selection'];
            // $student_code = $_POST['student_code'];
            // เชื่อมต่อฐานข้อมูล
            session_start();
            include '/xampp/htdocs/Project_Final/server.php';
            if (!isset($_SESSION['username'])) {
                header("Location: admin_login.php");
                exit();
            }

            // ตรวจสอบการเชื่อมต่อ
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // ค้นหาข้อมูลผู้ใช้ตาม student_id หรือ student_code

            $student_id = $selection;
            $student_code = $selection;
            $f_name = $selection;
            $l_name = $selection;
            $sql = "SELECT * FROM student WHERE student_id = ? OR student_code = ? OR f_name = ? OR l_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $student_id, $student_code, $f_name, $l_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table class='table table-bordered mt-3'>";
                echo "<thead><tr><th>Student ID</th>
                <th>Student Code</th>
                <th>Password</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                </tr></thead>";

                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['f_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['l_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<div class='alert alert-danger mt-3'>No records found for ID Card Number: $student_id</div>";
            }
            $stmt->close();
            $conn->close();
        }
        ?>
        <?php
        // เชื่อมต่อฐานข้อมูล

        include '/xampp/htdocs/Project_Final/server.php';
        if (!isset($_SESSION['username'])) {
            header("Location: admin_login.php");
            exit();
        }

        // ตรวจสอบการเชื่อมต่อ
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // ดึงข้อมูลจากฐานข้อมูล
        $sql = "SELECT * FROM student";
        $result = $conn->query($sql);
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>แก้ไขข้อมูลนักเรียน</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>

        <body>
            <div class="container mt-5">
                <h2 class="mb-4">แก้ไขข้อมูลนักเรียน</h2>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Student ID</th>
                            <th>Student Code</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                            <td>" . htmlspecialchars($row["student_id"]) . "</td>
                            <td>" . htmlspecialchars($row["student_code"]) . "</td>
                            <td>" . htmlspecialchars($row["f_name"]) . "</td>
                            <td>" . htmlspecialchars($row["l_name"]) . "</td>
                            <td>" . htmlspecialchars($row["address"]) . "</td>
                            <td><a href='admin_edit_student_process.php?student_id=" . htmlspecialchars($row["student_id"]) . "' class='btn btn-primary'>แก้ไข</a></td>
                          </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>ไม่พบข้อมูล</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <a href="admin_dashboard.php" class="btn btn-secondary">กลับไปที่แดชบอร์ด</a>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>

        </html>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>