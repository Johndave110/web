<?php
session_start();
require_once "classes/Users.php";
require_once "classes/Profile.php";

$userObj = new Users();
$profileObj = new Profile();

$errors = [];
$username = "";
$password = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = trim(htmlspecialchars($_POST["username"]));
        $password = trim(htmlspecialchars($_POST["password"]));

        if(empty($username)){
                $errors['username'] = "Username is required";
        }

        if(empty($password)){
                $errors['password'] = "Password is required";
        }

        if(empty($errors)){
                $user = $userObj->login($username, $password);
                if($user){
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['username'] = $user['username'];

                        if($user['role'] === 'student'){
                                $profile = $profileObj->viewProfile($user['user_id']);
                                $_SESSION['gpa'] = isset($profile['gpa']) ? floatval($profile['gpa']) : 0.0;
                            header("Location: studash.php");
                                exit();
                        } elseif($user['role'] === 'admin'){
                            header("Location: dashboard.php");
                                exit();
                        }
                } else {
                        $errors['login'] = "Invalid username or password";
                }
        }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • Scholarship Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };
    </script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2">
                <span class="h-8 w-8 rounded bg-brand-blue/10 grid place-items-center text-brand-blue font-semibold">SP</span>
                <span class="font-semibold text-brand-blue">Scholarship Portal</span>
            </a>
            <a href="Register.php" class="hidden sm:inline-flex px-3 py-2 rounded-md bg-brand-blue text-white hover:bg-blue-700">Create account</a>
        </div>
    </nav>

    <main class="max-w-md mx-auto mt-12 px-4">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold mb-1">Welcome back</h1>
            <p class="text-slate-600 mb-6">Sign in to continue</p>

            <form action="" method="post" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                    <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>" required class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm" placeholder="yourname" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors['username'] ?? '' ?></p>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" id="password" required class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue shadow-sm" placeholder="••••••••" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors['password'] ?? '' ?></p>
                </div>
                <?php if(isset($errors['login'])): ?>
                    <div class="rounded-md bg-red-50 text-red-700 px-3 py-2 text-sm">
                        <?= $errors['login'] ?>
                    </div>
                <?php endif; ?>
                <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-md bg-brand-blue text-white font-semibold hover:bg-blue-700">Login</button>
            </form>
            <p class="text-sm text-slate-600 mt-4">Don't have an account?
                <a href="Register.php" class="text-brand-blue hover:underline">Register</a>
            </p>
        </div>
    </main>

    <footer class="text-center text-slate-500 text-sm mt-10 pb-6">
        &copy; <?php echo date('Y'); ?> Scholarship Portal
    </footer>
</body>
</html>
