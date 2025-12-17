<?php
require_once "classes/Users.php";

$userObj = new Users();
$errors = [];

$profile_id = $_GET['profile_id'] ?? null;

if (!$profile_id) {
    die("Profile ID not found. Please register first.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(htmlspecialchars($_POST["username"]));
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm_password"]);

    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long.";
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $userObj->username = $username;
        $userObj->password = $password; // ✅ no hashing here
        $userObj->role = "student";
        $userObj->profile_id = $profile_id;

        $result = $userObj->addUser();

        if ($result === true) {
            echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
            exit();
        } elseif ($result === 'duplicate') {
            $errors['username'] = "Username already taken.";
        } else {
            echo "Error creating account.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Username & Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };
    </script>
    <style>
        /* Minor helper for transitions on feedback text */
        .feedback { transition: color 150ms, opacity 150ms; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2">
                <span class="h-8 w-8 rounded bg-brand-blue/10 grid place-items-center text-brand-blue font-semibold">SP</span>
                <span class="font-semibold text-brand-blue">Scholarship Portal</span>
            </a>
            <a href="login.php" class="hidden sm:inline-flex px-3 py-2 rounded-md bg-brand-blue text-white hover:bg-blue-700">Sign in</a>
        </div>
    </nav>

    <main class="max-w-md mx-auto mt-10 px-4">
    <div class="w-full px-0">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-semibold text-brand-dark">Create Account</h1>
            <p class="text-sm text-gray-600">Set your username and password to continue.</p>
        </div>

        <form action="" method="post" onsubmit="return validatePasswords();" class="bg-white shadow rounded-xl p-6 space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue" autocomplete="username">
                <p class="mt-1 text-sm text-red-600 feedback"><?= $errors['username'] ?? '' ?></p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" onkeyup="checkStrength()" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue" autocomplete="new-password">
                <p class="mt-1 text-sm text-red-600 feedback"><?= $errors['password'] ?? '' ?></p>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" onkeyup="checkMatch()" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue" autocomplete="new-password">
                <p id="match-message" class="mt-1 text-sm feedback"></p>
                <p class="mt-1 text-sm text-red-600 feedback"><?= $errors['confirm_password'] ?? '' ?></p>
            </div>

            <input type="hidden" name="role" value="student">

            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 bg-brand-blue hover:bg-blue-700 text-white font-medium rounded-md px-4 py-2 shadow">
                Create Account
            </button>
        </form>
        </div>
        <footer class="text-center text-slate-500 text-sm mt-10 pb-6">
            &copy; <?php echo date('Y'); ?> Scholarship Portal
        </footer>
    </main>
    <script>
        // ✅ Password Match Checker
        function checkMatch() {
            const pass = document.getElementById("password").value;
            const confirm = document.getElementById("confirm_password").value;
            const message = document.getElementById("match-message");

            if (confirm.length === 0) {
                message.textContent = "";
                return;
            }

            if (pass === confirm) {
                message.style.color = "green";
                message.textContent = "✅ Passwords match";
            } else {
                message.style.color = "red";
                message.textContent = "❌ Passwords do not match";
            }
        }

        // ✅ Optional: Password Strength Indicator
        function checkStrength() {
            const pass = document.getElementById("password").value;
            const message = document.getElementById("match-message");

            if (pass.length < 8) {
                message.className = "mt-1 text-sm text-orange-600 feedback";
                message.textContent = "⚠️ Password should be at least 8 characters";
            } else {
                message.textContent = "";
            }
        }

        // ✅ Prevent submission if passwords don't match
        function validatePasswords() {
            const pass = document.getElementById("password").value;
            const confirm = document.getElementById("confirm_password").value;

            if (pass !== confirm) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
