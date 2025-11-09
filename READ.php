<?php
require 'config.php';

function fetchStudents(PDO $pdo): array {
    try {
        $query = "SELECT * FROM students ORDER BY id DESC";
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching data: " . $e->getMessage());
    }
}

$students = fetchStudents($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Records</title>
<style>
    body {
        background-color: #121212;
        color: #f0f0f0;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 40px;
    }
    h2 {
        color: #00adb5;
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: #1f1f1f;
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #333;
    }
    th {
        background: #00adb5;
        color: #000;
    }
    tr:hover {
        background: #2c2c2c;
    }
    .btn {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 5px;
        font-weight: bold;
        color: #fff;
        text-decoration: none;
    }
    .edit {
        background: #4caf50;
    }
    .delete {
        background: #f44336;
    }
    .edit:hover {
        background: #3e8e41;
    }
    .delete:hover {
        background: #c62828;
    }
    .back-link {
        display: block;
        margin-top: 20px;
        text-align: center;
        color: #00adb5;
        text-decoration: none;
    }
    .back-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<h2>Student Records</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Student No</th>
            <th>Full Name</th>
            <th>Branch</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Date Added</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($students)): ?>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['id']) ?></td>
                    <td><?= htmlspecialchars($student['student_no']) ?></td>
                    <td><?= htmlspecialchars($student['fullname']) ?></td>
                    <td><?= htmlspecialchars($student['branch']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= htmlspecialchars($student['contact']) ?></td>
                    <td><?= htmlspecialchars($student['date_added']) ?></td>
                    <td>
                        <a href="update.php?id=<?= $student['id'] ?>" class="btn edit">Edit</a>
                        <a href="delete.php?id=<?= $student['id'] ?>" class="btn delete"
                           onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="text-align:center;">No student records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<a href="index.php" class="back-link">← Back to Homepage</a>
</body>
</html>
