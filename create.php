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
    * { box-sizing: border-box; margin:0; padding:0; }
    body {
        font-family: "Poppins", Arial, sans-serif;
        background: #121212;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        color: #f5f5f5;
    }

    .form-card {
        background: #1e1e1e;
        width: 400px;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(255,102,178,0.3);
    }

    h2 {
        text-align: center;
        color: #ff66b2;
        margin-bottom: 25px;
    }

    .input-group {
        position: relative;
        margin-top: 15px;
    }

    .input-group i {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: #ff66b2;
        font-size: 16px;
    }

    input, select {
        width: 100%;
        padding: 12px 12px 12px 38px;
        margin-top: 5px;
        border-radius: 8px;
        border: none;
        background: #262626;
        color: #fff;
        font-size: 14px;
    }

    input[type="submit"] {
        width: 100%;
        margin-top: 20px;
        background: #ff66b2;
        color: #121212;
        font-weight: bold;
        padding: 12px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
    }

    input[type="submit"]:hover {
        background: #ff3399;
    }

    .error-box, .success {
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 6px;
    }

    .error-box { background: #331010; border:1px solid #5c1c1c; color: #ffb3b3; }
    .success { background: #1f2e1f; border:1px solid #235c32; color: #b7f0c8; text-align:center; }

    a.back {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #ff66b2;
        text-decoration: none;
    }
    a.back:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="form-card">
    <h2>Add Student Record</h2>

    <?php if ($errors): ?>
        <div class="error-box">
            <ul style="margin:0; padding-left:18px;">
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
            'student_no' => ['label'=>'Student Number','icon'=>'fa-id-card'],
            'fullname'   => ['label'=>'Full Name','icon'=>'fa-user'],
            'branch'     => ['label'=>'Branch','icon'=>'fa-school'],
            'email'      => ['label'=>'Email','icon'=>'fa-envelope'],
            'contact'    => ['label'=>'Contact','icon'=>'fa-phone']
        ];
        ?>

        <?php foreach ($fields as $key => $data): ?>
            <div class="input-group">
                <i class="fa <?= $data['icon'] ?>"></i>
                <?php if ($key === 'branch'): ?>
                    <select id="<?= $key ?>" name="<?= $key ?>" required>
                        <option value="">Select Branch</option>
                        <?php foreach (['BSIT','BSTM','BSAMT','BSAE'] as $b): ?>
                            <option value="<?= $b ?>" <?= $formData[$key] === $b ? 'selected' : '' ?>><?= $b ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input id="<?= $key ?>" name="<?= $key ?>" type="<?= $key==='email'?'email':'text' ?>" value="<?= htmlspecialchars($formData[$key]) ?>" placeholder="<?= $data['label'] ?>" required>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <input type="submit" value="Save Student">
    </form>

    <a href="index.php" class="back">← Back to Homepage</a>
</div>

</body>
</html>