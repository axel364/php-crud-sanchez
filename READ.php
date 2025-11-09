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
    * { box-sizing: border-box; margin:0; padding:0; }
    body {
        font-family: "Poppins", Arial, sans-serif;
        background: #121212;
        color: #f5f5f5;
        display: flex;
        justify-content: center;
        padding: 40px 20px;
    }

    .table-card {
        width: 100%;
        max-width: 1000px;
        background: #1e1e1e;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(255,102,178,0.3);
        overflow-x: auto;
    }

    h2 {
        text-align: center;
        color: #ff66b2;
        margin-bottom: 25px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #1f1f1f;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #333;
    }

    th {
        background: #ff66b2;
        color: #121212;
    }

    tr:hover {
        background: #2c2c2c;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        color: #fff;
        transition: 0.2s;
    }

    .edit { background: #4caf50; }
    .delete { background: #f44336; }

    .edit:hover { background: #3e8e41; }
    .delete:hover { background: #c62828; }

    a.back {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #ff66b2;
        text-decoration: none;
    }
    a.back:hover { text-decoration: underline; }

    @media(max-width:768px){
        th, td { font-size: 13px; padding: 10px; }
        .btn { padding: 5px 10px; font-size: 13px; }
    }
</style>
</head>
<body>

<div class="table-card">
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
                            <a href="update.php?id=<?= $student['id'] ?>" class="btn edit"><i class="fa fa-pen"></i>Edit</a>
                            <a href="delete.php?id=<?= $student['id'] ?>" class="btn delete" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i>Delete</a>
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

    <a href="index.php" class="back">← Back to Homepage</a>
</div>

</body>
</html>