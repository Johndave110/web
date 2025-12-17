<?php
session_start();
require_once "classes/Scholarship.php";

// Admin check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

$scholarObj = new Scholarship();
$errors = [];
$scholarship = [];

// Get scholarship ID from URL
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: scholarmanagement.php");
    exit();
}

$id = intval($_GET['id']);
$scholarship = $scholarObj->getScholarshipById($id);

if(!$scholarship){
    header("Location: scholarmanagement.php");
    exit();
}

// Handle form submission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $scholarship["title"] = trim(htmlspecialchars($_POST["title"]));
    $scholarship["description"] = trim(htmlspecialchars($_POST["description"]));
    $scholarship["requirements"] = trim(htmlspecialchars($_POST["requirements"]));
    $scholarship["deadline"] = trim(htmlspecialchars($_POST["deadline"]));
    $scholarship["total_slots"] = trim(htmlspecialchars($_POST["total_slots"]));
    $scholarship["min_gpa"] = trim(htmlspecialchars($_POST["min_gpa"]));
    $scholarship["available_slots"] = $scholarship["total_slots"]; // Adjust if needed

    // Validation
    if(empty($scholarship["title"])){
        $errors["title"] = "Scholarship title is required";
    }

    if(empty($scholarship["description"])){
        $errors["description"] = "Please enter a description";
    }

    if(empty($scholarship["requirements"])){
        $errors["requirements"] = "Requirements are required";
    }

    if(empty($scholarship["deadline"])){
        $errors["deadline"] = "Deadline is required";
    }

    if(!is_numeric($scholarship["total_slots"]) || $scholarship["total_slots"] < 0){
        $errors["total_slots"] = "Total slots must be a non-negative number";
    }

    if(!is_numeric($scholarship["min_gpa"]) || $scholarship["min_gpa"] < 0 || $scholarship["min_gpa"] > 5){
        $errors["min_gpa"] = "Please enter a valid GPA between 0 and 5";
    }

    // Update if no errors
    if(empty($errors)){
        $scholarObj->title = $scholarship["title"];
        $scholarObj->description = $scholarship["description"];
        $scholarObj->requirements = $scholarship["requirements"];
        $scholarObj->deadline = $scholarship["deadline"];
        $scholarObj->total_slots = $scholarship["total_slots"];
        $scholarObj->available_slots = $scholarship["available_slots"];
        $scholarObj->min_gpa = $scholarship["min_gpa"];

        if($scholarObj->updateScholarship($id)){
            echo "<script>alert('Scholarship updated successfully!'); window.location.href='scholarmanagement.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating scholarship');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Scholarship</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body class="bg-slate-50 text-slate-800">

<?php include_once __DIR__ . '/nav-admin.php'; ?>

<div class="max-w-3xl mx-auto px-6 mt-8">
    <a href="scholarmanagement.php" class="inline-flex items-center text-brand-blue hover:underline">&larr; Back to Scholarships</a>
    <h2 class="text-2xl font-bold mt-2 mb-4">Edit Scholarship</h2>

    <form action="" method="post" class="bg-white rounded-xl shadow p-6 space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium">Scholarship Title <span class="text-red-600">*</span></label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($scholarship["title"]) ?>" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
            <p class="text-sm text-red-600 mt-1"><?= $errors["title"] ?? "" ?></p>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium">Description <span class="text-red-600">*</span></label>
            <textarea name="description" id="description" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" rows="4"><?= htmlspecialchars($scholarship["description"]) ?></textarea>
            <p class="text-sm text-red-600 mt-1"><?= $errors["description"] ?? "" ?></p>
        </div>

        <div>
            <label for="requirements" class="block text-sm font-medium">Document Requirements <span class="text-red-600">*</span></label>
            <textarea name="requirements" id="requirements" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" rows="4"><?= htmlspecialchars($scholarship["requirements"]) ?></textarea>
            <p class="text-sm text-red-600 mt-1"><?= $errors["requirements"] ?? "" ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="min_gpa" class="block text-sm font-medium">Minimum GPA Requirement <span class="text-red-600">*</span></label>
                <input type="number" step="0.01" name="min_gpa" id="min_gpa" value="<?= htmlspecialchars($scholarship["min_gpa"]) ?>" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                <p class="text-sm text-red-600 mt-1"><?= $errors["min_gpa"] ?? "" ?></p>
            </div>
            <div>
                <label for="deadline" class="block text-sm font-medium">Deadline <span class="text-red-600">*</span></label>
                <input type="date" name="deadline" id="deadline" value="<?= htmlspecialchars($scholarship["deadline"]) ?>" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                <p class="text-sm text-red-600 mt-1"><?= $errors["deadline"] ?? "" ?></p>
            </div>
            <div>
                <label for="total_slots" class="block text-sm font-medium">Total Slots <span class="text-red-600">*</span></label>
                <input type="number" name="total_slots" id="total_slots" value="<?= htmlspecialchars($scholarship["total_slots"]) ?>" class="mt-1 w-full rounded-md border-slate-300 focus:border-brand-blue focus:ring-brand-blue" />
                <p class="text-sm text-red-600 mt-1"><?= $errors["total_slots"] ?? "" ?></p>
            </div>
        </div>

        <div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-md bg-brand-blue text-white hover:bg-blue-700">Update Scholarship</button>
        </div>
    </form>
</div>

</body>
<script src="scripts.min.js"></script>
</html>
