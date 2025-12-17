<?php
session_start();
require_once "classes/scholarship.php";
require_once "classes/Applications.php";
require_once "classes/Profile.php";

// Ensure student is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Get student info
$student_id = $_SESSION['user_id'];
$profileObj = new Profile();
$studentProfile = $profileObj->viewProfile($student_id);
$studentGPA = isset($studentProfile['gpa']) ? floatval($studentProfile['gpa']) : 0.0;

$scholarObj = new Scholarship();
$appObj = new Application(); // Correct class name

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$scholarships = $scholarObj->getScholarships($limit, $offset);
$total = $scholarObj->countScholarships();
$totalPages = ceil($total / $limit);

// Handle file upload and application submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scholarship_id'])) {
    $scholarship_id = intval($_POST['scholarship_id']);
    $scholarship = $scholarObj->getScholarshipById($scholarship_id);

    if (!$scholarship) {
        die("<script>alert('Scholarship not found'); window.location.href='browsescholarships.php';</script>");
    }

    // GPA check
    if ($studentGPA < $scholarship['min_gpa']) {
        echo "<script>alert('Your GPA (" . number_format($studentGPA,2) . ") does not meet the minimum requirement of " . number_format($scholarship['min_gpa'],2) . "');</script>";
    } 
    // Already applied check
    elseif ($appObj->hasApplied($student_id, $scholarship_id)) {
        echo "<script>alert('You have already applied for this scholarship');</script>";
    } 
    else {
        // File upload
        if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['upload_file']['tmp_name'];
            $fileName = $_FILES['upload_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg','jpeg','png','pdf'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo "<script>alert('Invalid file type. Only JPG, PNG, PDF allowed');</script>";
            } else {
                $newFileName = $student_id . "_" . time() . "." . $fileExtension;
                $uploadDir = "uploads/applications/";
                if (!is_dir($uploadDir)) mkdir($uploadDir,0777,true);
                $uploadedFilePath = $uploadDir . $newFileName;
                move_uploaded_file($fileTmpPath, $uploadedFilePath);

                // Save application
                $appObj->student_id = $student_id;
                $appObj->scholarship_id = $scholarship_id;
                $appObj->upload_file = $uploadedFilePath;
                $appObj->status = 'Pending';
                $appObj->applied_at = date('Y-m-d H:i:s');
                $appObj->addApplication();

                echo "<script>alert('Application submitted successfully!'); window.location.href='tracking.php';</script>";
                exit();
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
<title>Browse Scholarships</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body class="bg-slate-50 text-slate-800">

<?php include_once __DIR__ . '/nav-student.php'; ?>

<div class="max-w-7xl mx-auto px-6 mt-8">
        <h2 class="text-2xl font-bold mb-4">Available Scholarships</h2>
    <?php if (empty($scholarships)) : ?>
                <p>No scholarships available.</p>
    <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($scholarships as $sch) : ?>
                        <div class="rounded-xl bg-white p-6 shadow">
                            <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($sch['title']) ?></h3>
                            <p class="text-slate-700"><span class="font-medium">Description:</span> <?= htmlspecialchars($sch['description']) ?></p>
                            <p class="text-slate-700"><span class="font-medium">Requirements:</span> <?= htmlspecialchars($sch['requirements']) ?></p>
                            <p class="text-slate-700"><span class="font-medium">Minimum GPA:</span> <?= number_format($sch['min_gpa'],2) ?></p>
                            <p class="text-slate-700"><span class="font-medium">Deadline:</span> <?= htmlspecialchars($sch['deadline']) ?></p>
                            <div class="mt-4">
                                <button class="inline-flex items-center px-4 py-2 rounded-md bg-brand-green text-white hover:bg-green-600" onclick="openModal(<?= $sch['scholarship_id'] ?>, <?= $sch['min_gpa'] ?>)">Apply</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
    <?php endif; ?>

    <!-- Pagination -->
        <?php if ($totalPages > 1) : ?>
            <div class="mt-6 flex items-center gap-2">
                <?php for ($i=1; $i<=$totalPages; $i++): ?>
                    <?php if($i==$page): ?>
                        <span class="px-3 py-1 rounded bg-brand-blue text-white"><?= $i ?></span>
                    <?php else: ?>
                        <a class="px-3 py-1 rounded border border-slate-300 hover:bg-slate-50" href="?page=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
</div>

<!-- Modal -->
<div id="applyModal" class="fixed inset-0 hidden bg-black/50 items-center justify-center">
    <div class="bg-white rounded-xl shadow p-6 w-full max-w-md">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold">Upload Document</h3>
            <button class="text-slate-500 hover:text-slate-700" onclick="closeModal()">&times;</button>
        </div>
        <form id="applyForm" method="post" enctype="multipart/form-data" class="space-y-3">
            <input type="file" name="upload_file" id="upload_file" required class="w-full" />
            <p id="fileName" class="text-sm text-slate-600"></p>
            <input type="hidden" name="scholarship_id" id="scholarship_id">
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-brand-blue text-white hover:bg-blue-700">Submit Application</button>
        </form>
    </div>
</div>

<script>
const studentGPA = <?= $studentGPA ?>;

// Open modal with GPA check
function openModal(scholarshipId, minGPA){
    if(studentGPA < minGPA){
        alert(`Your GPA (${studentGPA.toFixed(2)}) does not meet the minimum requirement of ${minGPA.toFixed(2)}`);
        return;
    }
    document.getElementById('scholarship_id').value = scholarshipId;
    const m = document.getElementById('applyModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}

// Close modal
function closeModal(){
    const m = document.getElementById('applyModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
    document.getElementById('fileName').textContent = '';
    document.getElementById('upload_file').value = '';
}

// Show selected file name
document.getElementById('upload_file').addEventListener('change', function() {
    const file = this.files[0];
    if(file){
        document.getElementById('fileName').textContent = "Selected file: " + file.name;
    } else {
        document.getElementById('fileName').textContent = '';
    }
});
</script>

</body>
<script src="scripts.min.js"></script>
</html>
