<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Branch Directory System</title>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: "Poppins", Arial, sans-serif;
        background: #121212;
        color: #f5f5f5;
        display: flex;
        height: 100vh;
    }

    .sidebar {
        background: #1c1c1c;
        width: 250px;
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        box-shadow: 2px 0 10px rgba(255, 192, 203, 0.2);
    }

    .sidebar h2 {
        color: #ff66b2;
        margin-bottom: 25px;
        text-align: left;
        font-size: 1.4em;
        letter-spacing: 1px;
    }

    .sidebar a {
        display: block;
        width: 100%;
        padding: 12px 15px;
        margin: 8px 0;
        text-decoration: none;
        color: #fff;
        background: #262626;
        border-radius: 6px;
        font-weight: 500;
        transition: 0.3s ease;
    }

    .sidebar a:hover {
        background: #ff66b2;
        color: #1c1c1c;
        transform: translateX(5px);
    }

    .content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: radial-gradient(circle at center, #1a1a1a, #0d0d0d);
        text-align: center;
    }

    .content h1 {
        font-size: 2.3em;
        color: #ff66b2;
        margin-bottom: 10px;
        text-shadow: 0 0 10px rgba(255, 102, 178, 0.4);
    }

    .content p {
        color: #aaa;
        font-size: 1em;
        max-width: 500px;
    }

    footer {
        position: fixed;
        bottom: 10px;
        left: 270px;
        font-size: 0.9em;
        color: #777;
    }
</style>
</head>
<body>

    <div class="sidebar">
        <h2>📚 Student Directory</h2>
        <a href="create.php">➕ Add Student</a>
        <a href="read.php">📋 View Students</a>
        <a href="read.php">✏️ Edit Student</a>
        <a href="read.php">🗑️ Delete Student</a>
    </div>

    <div class="content">
        <h1>Welcome to the Student Branch Directory</h1>
        <p>Manage student records easily. You can add, view, edit, or delete data using the sidebar menu.</p>
    </div>

</body>
</html>