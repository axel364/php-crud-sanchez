<?php
require 'config.php';

function getStudent(PDO $pdo, int $id): ?array {
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $e) {
        die("Error fetching record: " . $e->getMessage());
    }
}

function updateStudent(PDO $pdo, array $data, int $id): bool {
    try {
        $query = "
            UPDATE students 
            SET student_no = :student_no,
                fullname   = :fullname,
                branch     = :branch,
                email      = :email,
                contact    = :contact
            WHERE id = :id
        ";
        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            ':student_no' => $data['student_no'],
            ':fullname'   => $data['fullname'],
            ':branch'     => $data['branch'],
            ':email'      => $data['email'],
            ':contact'    => $data['contact'],
            ':id'         => $id
        ]);
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Update failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        return false;
    }
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<p style='color:red;text-align:center;'>❌ Error: Invalid student ID.<br><a href='read.php'>Back</a></p>");
}

$id = (int)$_GET['id'];
$student = getStudent($pdo, $id);
if (!$student) {
    die("<p style='color:red;text-align:center;'>❌ Student not found.<br><a href='read.php'>Back</a></p>");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = [
        'student_no' => trim($_POST['student_no']),
        'fullname'   => trim($_POST['fullname']),
        'branch'     => trim($_POST['branch']),
        'email'      => trim($_POST['email']),
        'contact'    => trim($_POST['contact'])
    ];

    if (updateStudent($pdo, $input, $id)) {
        echo "<script>alert('✅ Student record updated successfully!'); window.location='read.php';</script>";
        exit;
    } else {
        $message = "<p style='color:red;text-align:center;'>❌ Failed to update student record.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Student</title>
<style>
    * { box-sizing: border-box; margin:0; padding:0; }
    body {
        font-family: Arial, sans-serif;
        background: #121212;
        color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .form-card {
        background: #1f1f1f;
        padding: 30px 25px;
        border-radius: 12px;
        width: 360px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.5);
    }
    h2 {
        text-align: center;
        color: #00adb5;
        margin-bottom: 20px;
    }
    .input-group {
        position: relative;
        margin-bottom: 15px;
    }
    .input-group input,
    .input-group select {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border-radius: 8px;
        border: none;
        background: #2c2c2c;
        color: #fff;
        font-size: 14px;
    }
    .input-group svg {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        fill: #888;
        width: 18px;
        height: 18px;
    }
    input[type="submit"] {
        width: 100%;
        padding: 12px;
        background: #ff66b2;
        border: none;
        border-radius: 8px;
        color: #121212;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
        transition: background 0.3s, transform 0.2s;
    }
    input[type="submit"]:hover {
        background: #ff3399;
        transform: translateY(-2px);
    }
    a.back-link {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #00adb5;
        text-decoration: none;
    }
    a.back-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="form-card">
    <h2>Edit Student</h2>

    <form method="POST">
        <div class="input-group">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            <input id="student_no" name="student_no" type="text" placeholder="Student Number" value="" required>
        </div>

        <div class="input-group">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            <input id="fullname" name="fullname" type="text" placeholder="Full Name" value="" required>
        </div>

        <div class="input-group">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 6h18v2H3zm0 5h18v2H3zm0 5h18v2H3z"/></svg>
            <select id="branch" name="branch" required>
                <option value="">Select Branch</option>
                <option value="BSIT">BSIT</option>
                <option value="BSCS">BSCS</option>
                <option value="BSCE">BSCE</option>
                <option value="BSECE">BSECE</option>
            </select>
        </div>

        <div class="input-group">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            <input id="email" name="email" type="email" placeholder="Email" value="" required>
        </div>

        <div class="input-group">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1v3.5c0 .55-.45 1-1 1C10.75 21 3 13.25 3 4.5 3 3.95 3.45 3.5 4 3.5H7.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.12.35.03.74-.24 1.02l-2.2 2.2z"/></svg>
            <input id="contact" name="contact" type="text" placeholder="Contact" value="" required>
        </div>

        <input type="submit" value="Update Record">
    </form>

    <a href="read.php" class="back-link">← Back to Student List</a>
</div>

</body>
</html>