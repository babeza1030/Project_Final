<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New officer</title>
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
    <div class="container mt-5">
        <h2 class="text-center">Add New officer</h2>
        <form action="adminadd_teacher.php" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="officer_id">officer_id</label>
                <input type="text" class="form-control" id="officer_id" name="officer_id" required>
            </div>
            <div class="form-group">
                <label for="officer_password">Password</label>
                <input type="password" class="form-control" id="officer_password" name="officer_password" required>
            </div>
            <div class="form-group">
                <label for="f_name">f_name</label>
                <input type="text" class="form-control" id="f_name" name="f_name" required>
            </div>
            <div class="form-group">
                <label for="l_name">l_name</label>
                <input type="text" class="form-control" id="l_name" name="l_name" required>
            </div>
            <div class="form-group">
                <label for="campus">campus</label>
                <input type="text" class="form-control" id="campus" name="campus" required>
            </div>
            <div class="form-group">
                <label for="room_number">room_number</label>
                <input type="text" class="form-control" id="room_number" name="room_number" required>
            </div>
            <div class="form-group">
                <label for="position">position</label>
                <input type="text" class="form-control" id="position" name="position" required>
            </div>
            <div class="form-group">
                <label for="file">Image<input type="file" class="form-control" id="file" name="file" accept="image/*" hidden>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Add officer</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $officer_id = $_POST['officer_id'];
        $officer_password = password_hash($_POST['officer_password'], PASSWORD_BCRYPT);
        $f_name = $_POST['f_name'];
        $l_name = $_POST['l_name'];
        $campus = $_POST['campus'];
        $room_number = $_POST['room_number'];
        $position = $_POST['position'];

        // Handle file upload
        $profile_image = '';
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $uploads_dir = '/xampp/htdocs/Project_Final/uploads';
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true);
            }
            $profile_image = $uploads_dir . '/' . basename($_FILES['file']['name']);
            move_uploaded_file($_FILES['file']['tmp_name'], $profile_image);
        }

        // Database connection
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

        $sql = "INSERT INTO teacher (officer_id, password, f_name, l_name, campus, room_number, position, profile_image) VALUES ('$officer_id', '$officer_password', '$f_name', '$l_name', '$campus', '$room_number', '$position', '$profile_image')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>New officer added successfully</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }

        $conn->close();
    }
    ?>
</body>

</html>