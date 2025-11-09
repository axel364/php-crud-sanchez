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
    * { box-sizing: border-box; margin:0; padding:0; }

body {
    font-family: "Poppins", Arial, sans-serif;
    background: #121212;
    color: #f5f5f5;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Top slide-down panel */
.delete-top-panel {
    background: #1f1f1f;
    border-bottom: 3px solid #ff4d6d;
    padding: 25px 30px;
    animation: slideDown 0.5s ease forwards;
    text-align: center;
}

@keyframes slideDown {
    from { transform: translateY(-100%); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.delete-top-panel h2 {
    color: #ff4d6d;
    font-size: 1.6em;
    margin-bottom: 12px;
}

.delete-top-panel p {
    margin: 10px 0;
}

.delete-top-panel .highlight {
    color: #00ffd1;
    font-weight: bold;
}

.delete-top-panel .alert {
    background: rgba(255, 77, 109, 0.1);
    color: #ff4d6d;
    padding: 10px;
    border-radius: 6px;
    margin: 12px 0;
}

.delete-top-panel form {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}

button.confirm {
    background: #ff4d6d;
    color: #fff;
    border: none;
    padding: 12px 22px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
}

button.confirm:hover {
    background: #ff6f8b;
}

a.cancel {
    display: inline-block;
    padding: 12px 22px;
    border-radius: 6px;
    background: #555;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

a.cancel:hover {
    background: #777;
}

/* Main content */
.content {
    flex: 1;
    padding: 30px;
    text-align: center;
}

@media(max-width:600px){
    .delete-top-panel form { flex-direction: column; }
    button.confirm, a.cancel { width: 100%; }
}
</style>
</head>
<body>

<div class="delete-top-panel">
    <h2>🛑 Delete Student</h2>
    <p>Are you sure you want to delete this student?</p>
    <p class="highlight"><?= htmlspecialchars($student['fullname']); ?></p>
    <p class="alert">This action cannot be undone.</p>

    <form method="POST">
        <button type="submit" name="confirm" class="confirm">Yes, Delete</button>
        <a href="read.php" class="cancel">Cancel</a>
    </form>
</div>

</body>
</html>