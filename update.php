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
    body {
        background: #121212;
        color: #eee;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .form-container {
        background: #1f1f1f;
        padding: 25px;
        border-radius: 10px;
        width: 350px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.5);
    }
    h2 {
        color: #00adb5;
        text-align: center;
        margin-bottom: 15px;
    }
    label {
        display: block;
        font-weight: bold;
        margin-top: 10px;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border: none;
        border-radius: 6px;
        background: #2c2c2c;
        color: #fff;
        box-sizing: border-box;
    }
    input[type="submit"] {
        background: #00adb5;
        color: #000;
        font-weight: bold;
        cursor: pointer;
        margin-top: 15px;
        transition: background 0.3s;
    }
    input[type="submit"]:hover {
        background: #019aa1;
    }
    a {
        display: block;
        text-align: center;
        color: #00adb5;
        margin-top: 15px;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="form-container">
    <h2>Edit Student</h2>
    <?= $message ?>

    <form method="POST">
        <label for="student_no">Student No</label>
        <input id="student_no" name="student_no" type="text"
               value="<?= htmlspecialchars($student['student_no']) ?>" required>

        <label for="fullname">Full Name</label>
        <input id="fullname" name="fullname" type="text"
               value="<?= htmlspecialchars($student['fullname']) ?>" required>

        <label for="branch">Branch</label>
        <select id="branch" name="branch" required>
            <?php foreach (['BSIT','BSCS','BSCE','BSECE'] as $b): ?>
                <option value="<?= $b ?>" <?= $student['branch'] === $b ? 'selected' : '' ?>>
                    <?= $b ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="email">Email</label>
        <input id="email" name="email" type="email"
               value="<?= htmlspecialchars($student['email']) ?>" required>

        <label for="contact">Contact</label>
        <input id="contact" name="contact" type="text"
               value="<?= htmlspecialchars($student['contact']) ?>" required>

        <input type="submit" value="Update Record">
    </form>

    <a href="read.php">← Back to Student List</a>
</div>

</body>
</html>
