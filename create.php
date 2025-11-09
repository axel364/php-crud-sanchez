<?php
require 'config.php';

$alert = '';
$errors = [];
$formData = [
    'student_no' => '',
    'fullname'   => '',
    'branch'     => '',
    'email'      => '',
    'contact'    => ''
];

function sanitizeInput($key, $filter) {
    return trim(filter_input(INPUT_POST, $key, $filter) ?? '');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $formData['student_no'] = sanitizeInput('student_no', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $formData['fullname']   = sanitizeInput('fullname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $formData['branch']     = sanitizeInput('branch', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $formData['email']      = sanitizeInput('email', FILTER_SANITIZE_EMAIL);
    $formData['contact']    = sanitizeInput('contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $required = [
        'student_no' => 'Student Number is required.',
        'fullname'   => 'Full Name is required.',
        'branch'     => 'Branch is required.',
        'email'      => 'A valid Email is required.',
        'contact'    => 'Contact is required.'
    ];

    foreach ($required as $field => $message) {
        if ($formData[$field] === '' || ($field === 'email' && !filter_var($formData['email'], FILTER_VALIDATE_EMAIL))) {
            $errors[] = $message;
        }
    }

    if (!$errors) {
        try {
            $query = "
                INSERT INTO students (student_no, fullname, branch, email, contact, date_added)
                VALUES (:student_no, :fullname, :branch, :email, :contact, NOW())
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute($formData);

            $alert = "<p class='success'>✅ Student record added successfully.</p>";
            $formData = array_fill_keys(array_keys($formData), '');
        } catch (PDOException $e) {
            $errors[] = $e->getCode() == 23000
                ? "A record with that Student Number or Email already exists."
                : "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Student</title>
<style>
    body {
        background: #121212;
        color: #eaeaea;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }
    .form-box {
        background: #1e1e1e;
        width: 360px;
        padding: 24px;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.6);
    }
    h2 {
        text-align: center;
        color: #00adb5;
        margin-bottom: 14px;
    }
    label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-top: 10px;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        border-radius: 6px;
        border: none;
        background: #2c2c2c;
        color: #fff;
        box-sizing: border-box;
    }
    input[type="submit"] {
        background: #00adb5;
        color: #051017;
        font-weight: 700;
        cursor: pointer;
        margin-top: 16px;
        border: none;
    }
    .error-box, .success {
        padding: 8px 10px;
        border-radius: 6px;
        margin-bottom: 10px;
    }
    .error-box {
        background: #331010;
        border: 1px solid #5c1c1c;
        color: #ffb3b3;
    }
    .success {
        background: #0f2f19;
        border: 1px solid #235c32;
        color: #b7f0c8;
        text-align: center;
    }
    a {
        display: block;
        margin-top: 12px;
        text-align: center;
        color: #00adb5;
        text-decoration: none;
    }
</style>
</head>
<body>
<div class="form-box">
    <h2>Add Student Record</h2>

    <?php if ($errors): ?>
        <div class="error-box">
            <ul style="margin:0;padding-left:18px;">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?= $alert ?>

    <form method="post">
        <?php
        $fields = [
            'student_no' => 'Student Number',
            'fullname'   => 'Full Name',
            'branch'     => 'Branch',
            'email'      => 'Email',
            'contact'    => 'Contact'
        ];
        ?>

        <?php foreach ($fields as $key => $label): ?>
            <label for="<?= $key ?>"><?= $label ?></label>
            <?php if ($key === 'branch'): ?>
                <select id="branch" name="branch" required>
                    <option value="">Select Branch</option>
                    <?php foreach (['BSIT','BSTM','BSAMT','BSAE'] as $b): ?>
                        <option value="<?= $b ?>" <?= $formData['branch'] === $b ? 'selected' : '' ?>>
                            <?= $b ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?> 
                <input
                    id="<?= $key ?>"
                    name="<?= $key ?>"
                    type="<?= $key === 'email' ? 'email' : 'text' ?>"
                    value="<?= htmlspecialchars($formData[$key]) ?>"
                    required
                >
            <?php endif; ?>
        <?php endforeach; ?>

        <input type="submit" value="Save Student">
    </form>

    <a href="index.php">← Back to Homepage</a>
</div>
</body>
</html>