<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Borrower Status</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 50px;
        }

        .container {
            max-width: 800px;
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
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>