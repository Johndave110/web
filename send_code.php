<?php
session_start();
require_once __DIR__ . '/functions.php';

$errors = [];
$email = '';
$sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if ($email === '') {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email';
    } elseif (!preg_match('/@gmail\.com$/i', $email)) {
        $errors['email'] = 'Please use a Gmail address';
    } else {
        // Generate and store code
        $_SESSION['verify_code'] = random_int(100000, 999999);
        $_SESSION['verify_expires'] = time() + 600; // 10 minutes
        // Send email
        if (send_verification_email($email, $_SESSION['verify_code'])) {
            $sent = true;
            header('Location: verify_code.php');
            exit();
        } else {
            $errors['email'] = 'Failed to send email. Check SMTP credentials or logs.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Send Verification Code</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2">
                <span class="h-8 w-8 rounded bg-brand-blue/10 grid place-items-center text-brand-blue font-semibold">SP</span>
                <span class="font-semibold text-brand-blue">Scholarship Portal</span>
            </a>
        </div>
    </nav>

    <main class="max-w-md mx-auto mt-12 px-4">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold mb-1">Send a Verification Code</h1>
            <p class="text-slate-600 mb-6">Enter your email and we'll send a 6-digit code.</p>

            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm" placeholder="you@gmail.com" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors['email'] ?? '' ?></p>
                </div>
                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-md bg-brand-blue text-white font-semibold hover:bg-blue-700">Send Code</button>
            </form>
        </div>
    </main>
</body>
<script src="scripts.min.js"></script>
</html>
