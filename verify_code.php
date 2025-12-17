<?php
session_start();

$errors = [];
$codeInput = '';
$hasCode = isset($_SESSION['verify_code']);
$expires = isset($_SESSION['verify_expires']) ? (int)$_SESSION['verify_expires'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeInput = trim($_POST['code'] ?? '');
    if ($codeInput === '') {
        $errors['code'] = 'Verification code is required';
    } elseif (!preg_match('/^[0-9]{6}$/', $codeInput)) {
        $errors['code'] = 'Enter the 6-digit code';
    } elseif (!$hasCode) {
        $errors['code'] = 'No verification code has been set.';
    } elseif ($expires && time() > $expires) {
        $errors['code'] = 'Code expired.';
    } else {
        if ($codeInput === (string)$_SESSION['verify_code']) {
            $_SESSION['code_verified'] = true;
            $verified = true;
        } else {
            $errors['code'] = 'Incorrect code.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Code Verification</title>
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
            <h1 class="text-2xl font-bold mb-1">Enter Verification Code</h1>
            <p class="text-slate-600 mb-6">Please enter your 6-digit code.</p>

            <?php if (!$hasCode): ?>
                <div class="rounded-md bg-orange-50 text-orange-700 px-3 py-2 text-sm mb-4">No verification code is set in the session.</div>
            <?php endif; ?>

            <?php if (!empty($verified)): ?>
                <div class="rounded-md bg-green-50 text-green-700 px-3 py-2 text-sm mb-4">Code verified successfully.</div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700">Verification Code</label>
                    <input type="text" name="code" id="code" value="<?= htmlspecialchars($codeInput) ?>" maxlength="6" pattern="[0-9]{6}" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm" placeholder="123456" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors['code'] ?? '' ?></p>
                </div>
                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-md bg-brand-blue text-white font-semibold hover:bg-blue-700">Verify</button>
            </form>
        </div>
    </main>
</body>
<script src="scripts.min.js"></script>
</html>
