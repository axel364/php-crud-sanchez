<?php
require 'config.php';


$id = $_GET['id'] ?? null;
if (!$id) {
    exit("<p style='color:red; text-align:center;'>❌ No student ID provided.<br><a href='read.php'>Back</a></p>");
}

try {
    $query = $pdo->prepare("SELECT fullname FROM students WHERE id = ?");
    $query->execute([$id]);
    $student = $query->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        exit("<p style='color:red; text-align:center;'>❌ Student not found.<br><a href='read.php'>Back</a></p>");
    }
} catch (PDOException $error) {
    exit("Database error: " . $error->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    try {
        $remove = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $remove->execute([$id]);

        echo "<script>alert('✅ Student deleted successfully!'); window.location='read.php';</script>";
        exit;
    } catch (PDOException $error) {
        exit("Error deleting record: " . $error->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delete Student</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #121212;
        color: #eee;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .wrapper {
        background: #1f1f1f;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        width: 380px;
        box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
    }
    h2 {
        color: #f44336;
        margin-bottom: 15px;
    }
    .highlight {
        color: #00adb5;
        font-weight: bold;
    }
    .alert {
        background: #332222;
        color: #ffcc00;
        padding: 10px;
        border-radius: 5px;
        margin: 15px 0;
    }
    form { margin-top: 20px; }
    button {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        margin: 5px;
    }
    .confirm {
        background: #f44336;
        color: #fff;
    }
    .confirm:hover {
        background: #c62828;
    }
    .cancel {
        background: #555;
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 6px;
        display: inline-block;
    }
    .cancel:hover {
        background: #777;
    }
</style>
</head>
<body>

<div class="wrapper">
    <h2>⚠️ Confirm Deletion</h2>
    <p>Do you really want to delete this student?</p>
    <p class="highlight"><?= htmlspecialchars($student['fullname']); ?></p>
    <p class="alert">This action <strong>cannot be undone</strong>.</p>

    <form method="POST">
        <button type="submit" name="confirm" class="confirm">Yes, Delete</button>
        <a href="read.php" class="cancel">Cancel</a>
    </form>
</div>

</body>
</html>
