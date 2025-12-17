<?php
session_start();
require_once "classes/Applications.php";
require_once "classes/Profile.php";
require_once "classes/Scholarship.php";

// Admin check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

$appObj = new Application();
$profileObj = new Profile();
$scholarObj = new Scholarship();

// Summary counts
$totalStudents = $profileObj->countProfiles();
$totalScholarships = $scholarObj->countScholarships();
$totalApplications = $appObj->countApplications();
$totalApproved = $appObj->countApplicationsByStatus('Approved');
$totalRejected = $appObj->countApplicationsByStatus('Rejected');

// Recent applications
$recentApps = $appObj->getRecentApplications(10);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Reports</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body class="bg-slate-50 text-slate-800">

<!-- Navigation Bar -->
<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <img class="h-8 w-8" src="https://cdn-icons-png.flaticon.com/512/1828/1828911.png" alt="Admin Icon">
            <span class="font-semibold text-brand-blue">Admin Dashboard</span>
        </div>
        <div class="hidden md:flex items-center gap-6">
            <a href="dashboard.php" class="text-slate-700 hover:text-brand-blue">Overview</a>
            <a href="scholarmanagement.php" class="text-slate-700 hover:text-brand-blue">Scholarship Management</a>
            <a href="reviewapprove.php" class="text-slate-700 hover:text-brand-blue">Review & Approval</a>
            <a href="reports.php" class="text-brand-blue font-medium">Reports</a>
        </div>
        <a href="logout.php" class="inline-flex px-3 py-2 rounded-md bg-brand-blue text-white hover:bg-blue-700">Logout</a>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 mt-8">
    <h2 class="text-2xl font-bold mb-4">Admin Reports</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="rounded-xl bg-white p-4 shadow"><div class="text-slate-600 text-sm">Total Students</div><div class="text-2xl font-semibold"><?= $totalStudents ?></div></div>
        <div class="rounded-xl bg-white p-4 shadow"><div class="text-slate-600 text-sm">Total Scholarships</div><div class="text-2xl font-semibold"><?= $totalScholarships ?></div></div>
        <div class="rounded-xl bg-white p-4 shadow"><div class="text-slate-600 text-sm">Total Applications</div><div class="text-2xl font-semibold"><?= $totalApplications ?></div></div>
        <div class="rounded-xl bg-white p-4 shadow"><div class="text-slate-600 text-sm">Approved</div><div class="text-2xl font-semibold text-green-600"><?= $totalApproved ?></div></div>
        <div class="rounded-xl bg-white p-4 shadow"><div class="text-slate-600 text-sm">Rejected</div><div class="text-2xl font-semibold text-red-600"><?= $totalRejected ?></div></div>
    </div>

    <h3 class="text-xl font-semibold mb-3">Recent Applications</h3>
    <div class="overflow-x-auto bg-white rounded-xl shadow">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Student Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Scholarship</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Applied At</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">File</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach($recentApps as $index => $app): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 align-top"><?= $index + 1 ?></td>
                        <td class="px-4 py-3 align-top"><?= htmlspecialchars($app['firstName'] . ' ' . $app['lastName']) ?></td>
                        <td class="px-4 py-3 align-top"><?= htmlspecialchars($app['scholarship_title']) ?></td>
                        <td class="px-4 py-3 align-top">
                            <?php $status = strtolower($app['status']); ?>
                            <span class="font-semibold <?php if($status==='pending') echo 'text-orange-500'; elseif($status==='approved') echo 'text-green-600'; elseif($status==='rejected') echo 'text-red-600'; ?>">
                                <?= htmlspecialchars($app['status']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 align-top"><?= htmlspecialchars($app['applied_at']) ?></td>
                        <td class="px-4 py-3 align-top">
                            <?php if($app['upload_file']): ?>
                                <a class="text-brand-blue hover:underline" href="<?= htmlspecialchars($app['upload_file']) ?>" target="_blank">View</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
<script src="scripts.min.js"></script>
</html>
