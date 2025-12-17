<?php
require_once "classes/Profile.php";
$userObj = new Profile();

$profile = [];
$users = []; // Initialize users array
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $users["firstName"] = trim(htmlspecialchars($_POST["firstName"]));
    $users["lastName"] = trim(htmlspecialchars($_POST["lastName"]));
    $users["middleName"] = trim(htmlspecialchars($_POST["middleName"]));
    $users["birthdate"] = trim(htmlspecialchars($_POST["birthdate"]));
    $users["address"] = trim(htmlspecialchars($_POST["address"]));
    $users["contactNumber"] = trim(htmlspecialchars($_POST["contactNumber"]));
    $users["gpa"] = trim(htmlspecialchars($_POST["gpa"]));
    $users["familyIncome"] = trim(htmlspecialchars($_POST["familyIncome"]));
    $users["school"] = trim(htmlspecialchars($_POST["school"]));
    $users["course"] = trim(htmlspecialchars($_POST["course"]));
    $users["yearLevel"] = trim(htmlspecialchars($_POST["yearLevel"]));

    if (empty($users["firstName"])) {
        $errors["firstName"] = "First name is required";
    }

    if (empty($users["lastName"])) {
        $errors["lastName"] = "Last name is required";
    }

    if (empty($users["middleName"])) {
        $errors["middleName"] = "Middle name is required";
    }

    if (empty($users["birthdate"])) {
        $errors["birthdate"] = "Birthdate is required";
    }

    if (empty($users["address"])) {
        $errors["address"] = "Address is required";
    }

    if (empty($users["contactNumber"])) {
        $errors["contactNumber"] = "Contact number is required";
    } elseif (!preg_match('/^[0-9]+$/', $users["contactNumber"])) {
        $errors["contactNumber"] = "Digits only (0-9)";
    }

    if (empty($users["gpa"])) {
        $errors["gpa"] = "GPA is required";
    }

    if (empty($users["familyIncome"])) {
        $errors["familyIncome"] = "Family income is required";
    }

    if (empty($users["school"])) {
        $errors["school"] = "School name is required";
    }

    if (empty($users["course"])) {
        $errors["course"] = "Course is required";
    }

    if (empty($users["yearLevel"])) {
        $errors["yearLevel"] = "Year level is required";
    }

    if (empty($errors)) {
        $userObj->firstName = $users["firstName"];
        $userObj->lastName = $users["lastName"];
        $userObj->middleName = $users["middleName"];
        $userObj->birthdate = $users["birthdate"];
        $userObj->address = $users["address"];
        $userObj->contactNumber = $users["contactNumber"];
        $userObj->gpa = $users["gpa"];
        $userObj->familyIncome = $users["familyIncome"];
        $userObj->school = $users["school"];
        $userObj->course = $users["course"];
        $userObj->yearLevel = $users["yearLevel"];

        $profile_id = $userObj->addProfile();

        if ($profile_id) {
            header("Location: usernamepass.php?profile_id=" . $profile_id);
            exit();
        } else {
            // Note: The alert script should be placed inside the HTML <body> for safety
            // but since we are handling a post request, we'll put it here.
            echo "<script>alert('Error saving profile. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register • Scholarship Portal</title>
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
            <a href="login.php" class="hidden sm:inline-flex px-3 py-2 rounded-md bg-brand-blue text-white hover:bg-blue-700">Sign in</a>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto mt-10 px-4">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-bold mb-1">Create your profile</h1>
            <p class="text-slate-600 mb-6">Fields with <span class="text-red-600">*</span> are required</p>

            <form action="" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-1">
                    <label for="firstName" class="block text-sm font-medium">First Name <span class="text-red-600">*</span></label>
                    <input type="text" name="firstName" id="firstName" value="<?= htmlspecialchars($users["firstName"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["firstName"] ?? "" ?></p>
                </div>
                <div class="md:col-span-1">
                    <label for="lastName" class="block text-sm font-medium">Last Name <span class="text-red-600">*</span></label>
                    <input type="text" name="lastName" id="lastName" value="<?= htmlspecialchars($users["lastName"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["lastName"] ?? "" ?></p>
                </div>
                <div class="md:col-span-1">
                    <label for="middleName" class="block text-sm font-medium">Middle Name <span class="text-red-600">*</span></label>
                    <input type="text" name="middleName" id="middleName" value="<?= htmlspecialchars($users["middleName"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["middleName"] ?? "" ?></p>
                </div>
                <div class="md:col-span-1">
                    <label for="birthdate" class="block text-sm font-medium">Birthdate <span class="text-red-600">*</span></label>
                    <input type="date" name="birthdate" id="birthdate" value="<?= htmlspecialchars($users["birthdate"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["birthdate"] ?? "" ?></p>
                </div>
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium">Address <span class="text-red-600">*</span></label>
                    <input type="text" name="address" id="address" value="<?= htmlspecialchars($users["address"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["address"] ?? "" ?></p>
                </div>
                <div class="md:col-span-1">
                    <label for="contactNumber" class="block text-sm font-medium">Contact Number <span class="text-red-600">*</span></label>
                    <input type="text" name="contactNumber" id="contactNumber" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'');" value="<?= htmlspecialchars($users["contactNumber"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["contactNumber"] ?? "" ?></p>
                </div>
                <div class="md:col-span-1">
                    <label for="gpa" class="block text-sm font-medium">GPA <span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="gpa" id="gpa" value="<?= htmlspecialchars($users["gpa"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["gpa"] ?? "" ?></p>
                </div>
                <div class="md:col-span-1">
                    <label for="familyIncome" class="block text-sm font-medium">Family Income <span class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="familyIncome" id="familyIncome" value="<?= htmlspecialchars($users["familyIncome"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["familyIncome"] ?? "" ?></p>
                </div>
                <div class="md:col-span-2">
                    <label for="school" class="block text-sm font-medium">School <span class="text-red-600">*</span></label>
                    <input type="text" name="school" id="school" value="<?= htmlspecialchars($users["school"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["school"] ?? "" ?></p>
                </div>
                <div class="md:col-span-1">
                    <label for="course" class="block text-sm font-medium">Course <span class="text-red-600">*</span></label>
                    <input type="text" name="course" id="course" value="<?= htmlspecialchars($users["course"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["course"] ?? "" ?></p>
                </div>
                <div class="md:col-span-1">
                    <label for="yearLevel" class="block text-sm font-medium">Year Level <span class="text-red-600">*</span></label>
                    <input type="number" name="yearLevel" id="yearLevel" value="<?= htmlspecialchars($users["yearLevel"] ?? "") ?>" class="mt-1 p-2 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                    <p class="text-sm text-red-600 mt-1"><?= $errors["yearLevel"] ?? "" ?></p>
                </div>

                <div class="md:col-span-2 flex items-center gap-3 mt-2">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-md bg-brand-green text-white hover:bg-green-600">Register</button>
                    <a href="login.php" class="inline-flex items-center px-5 py-2.5 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Already have an account?</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-center text-slate-500 text-sm mt-10 pb-6">
        &copy; <?php echo date('Y'); ?> Scholarship Portal
    </footer>
</body>
</html>