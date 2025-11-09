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
        font-family: Arial, sans-serif;
        background: #121212;
        color: #f0f0f0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    h1 {
        color: #00adb5;
        font-size: 2.3em;
        margin-bottom: 40px;
        text-align: center;
    }
    .menu {
        background: #1e1e1e;
        border-radius: 10px;
        padding: 25px 35px;
        box-shadow: 0 0 15px rgba(0, 173, 181, 0.25);
        text-align: center;
    }
    .menu a {
        display: inline-block;
        margin: 8px 12px;
        padding: 12px 18px;
        background: #00adb5;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
        transition: 0.3s;
    }
    .menu a:hover {
        background: #019aa1;
        transform: scale(1.05);
    }
    footer {
        position: fixed;
        bottom: 12px;
        text-align: center;
        color: #777;
        font-size: 0.9em;
        width: 100%;
    }
</style>
</head>
<body>

    <h1>Student Branch Directory</h1>

    <div class="menu">
        <a href="create.php">Add Student</a>
        <a href="read.php">View Students</a>
        <a href="read.php">Edit Student</a>
        <a href="read.php">Delete Student</a>
    </div>

    <footer>
        &copy; <?= date('Y'); ?> Student Branch Directory. All Rights Reserved.
    </footer>

</body>
</html>
