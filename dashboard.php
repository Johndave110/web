<?php
session_start();
require_once "classes/Profile.php";
require_once "classes/Applications.php";
require_once "classes/Scholarship.php";

// Admin check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

$profileObj = new Profile();
$appObj = new Application();
$scholarObj = new Scholarship();

// Fetch 3 latest scholarships
$recentScholarships = $scholarObj->getRecentScholarships(3); 

// Fetch recent pending applications (direct DB query for efficiency)
$pendingApplications = $appObj->getRecentApplicationsByStatus('Pending', 3);

// Fetch counts for reports card (KPIs)
$totalStudents = $profileObj->countProfiles();
$totalScholarships = $scholarObj->countScholarships();
$totalApplications = $appObj->countApplications();
$totalApproved = $appObj->countApplicationsByStatus('Approved');
$totalRejected = $appObj->countApplicationsByStatus('Rejected');
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard Overview</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };
</script>
    
</head>
<body class="bg-slate-50 text-slate-800">

<?php include_once __DIR__ . '/nav-admin.php'; ?>

<div class="max-w-7xl mx-auto px-6 mt-8">
    <h2 class="text-2xl font-bold mb-4">Admin Overview</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-xl bg-white p-6 shadow cursor-pointer" onclick="location.href='scholarmanagement.php'">
            <h3 class="text-lg font-semibold mb-3">Scholarship Management</h3>
            <ul class="list-disc pl-5 text-slate-700 space-y-1">
                <?php foreach($recentScholarships as $sch): ?>
                    <li><?= htmlspecialchars($sch['title']) ?></li>
                <?php endforeach; ?>
            </ul>
            <p class="text-sm text-slate-500 mt-3">Click to manage</p>
        </div>

        <div class="rounded-xl bg-white p-6 shadow cursor-pointer" onclick="location.href='reviewapprove.php'">
            <h3 class="text-lg font-semibold mb-3">Review & Approval</h3>
            <ul class="list-disc pl-5 text-slate-700 space-y-1">
                <?php if(empty($pendingApplications)): ?>
                    <li>No pending applications</li>
                <?php else: ?>
                    <?php foreach($pendingApplications as $app): ?>
                        <li><?= htmlspecialchars($app['firstName'].' '.$app['lastName']).' - '.htmlspecialchars($app['status']) ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <p class="text-sm text-slate-500 mt-3">Click to review</p>
        </div>

        <div class="rounded-xl bg-white p-6 shadow cursor-pointer" onclick="location.href='reports.php'">
            <h3 class="text-lg font-semibold mb-3">Reports & Statistics</h3>
            <div class="space-y-1">
                <p>Total Students: <span class="font-semibold"><?= $totalStudents ?></span></p>
                <p>Total Scholarships: <span class="font-semibold"><?= $totalScholarships ?></span></p>
                <hr class="my-2">
                <p>Total Applications: <span class="font-semibold"><?= $totalApplications ?></span></p>
                <p class="text-green-600">Approved: <span class="font-semibold"><?= $totalApproved ?></span></p>
                <p class="text-red-600">Rejected: <span class="font-semibold"><?= $totalRejected ?></span></p>
            </div>
            <p class="text-sm text-slate-500 mt-3">Click for details</p>
        </div>
    </div>
</div>

</body>
<script src="scripts.min.js"></script>
</html>