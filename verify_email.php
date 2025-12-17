<?php
session_start();
require_once __DIR__ . '/classes/Profile.php';

$errors = [];
$codeInput = '';

// If signup_data missing, redirect back to Register
if (!isset($_SESSION['signup_data']) || !isset($_SESSION['verify_code'])) {
    header('Location: Register.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeInput = trim($_POST['code'] ?? '');
    if ($codeInput === '') {
        $errors['code'] = 'Verification code is required';
    } elseif (!preg_match('/^[0-9]{6}$/', $codeInput)) {
        $errors['code'] = 'Enter the 6-digit code';
    } else {
        $correct = (string)($_SESSION['verify_code'] ?? '');
        $expires = (int)($_SESSION['verify_expires'] ?? 0);
        if ($expires && time() > $expires) {
            $errors['code'] = 'Code expired. Please restart registration.';
        } elseif ($codeInput !== (string)$correct) {
            $errors['code'] = 'Incorrect code. Please try again.';
        } else {
            // Code valid: persist profile and continue
            $data = $_SESSION['signup_data'];
            $profile = new Profile();
            $profile->firstName = $data['firstName'];
            $profile->lastName = $data['lastName'];
            $profile->middleName = $data['middleName'];
            $profile->birthdate = $data['birthdate'];
            $profile->address = $data['address'];
            $profile->contactNumber = $data['contactNumber'];
            $profile->gpa = $data['gpa'];
            $profile->familyIncome = $data['familyIncome'];
            $profile->school = $data['school'];
            $profile->course = $data['course'];
            $profile->yearLevel = $data['yearLevel'];
            $profile_id = $profile->addProfile();
            // Clear verification/session data
            unset($_SESSION['signup_data'], $_SESSION['verify_code'], $_SESSION['verify_expires']);

            if ($profile_id) {
                header('Location: usernamepass.php?profile_id=' . $profile_id);
                exit();
            } else {
                $errors['code'] = 'Failed to save profile. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Email Verification</title>
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
            <h1 class="text-2xl font-bold mb-1">Verify your email</h1>
            <p class="text-slate-600 mb-6">Enter the 6-digit code we sent to <span class="font-semibold"><?= htmlspecialchars($_SESSION['signup_data']['email']) ?></span>.</p>

            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700">Verification Code</label>
                    <input type="text" name="code" id="code" value="<?= htmlspecialchars($codeInput) ?>" maxlength="6" pattern="[0-9]{6}" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm" placeholder="123456" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors['code'] ?? '' ?></p>
                </div>
                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-md bg-brand-blue text-white font-semibold hover:bg-blue-700">Verify & Continue</button>
            </form>
            <p class="text-sm text-slate-600 mt-4">Didn’t receive a code? Re-submit the registration form to resend.</p>
        </div>
    </main>
</body>
<script src="scripts.min.js"></script>
</html>
