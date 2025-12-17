<?php
session_start();
require_once "classes/Applications.php";
require_once "classes/Users.php";
require_once "classes/Scholarship.php";

// Ensure admin is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit();
}

$appObj = new Application();
$userObj = new Users();
$scholarObj = new Scholarship();

// Fetch all applications with student name and scholarship title
$applications = $appObj->getAllApplicationsWithDetails();

// Handle approve/reject actions
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'])){
    $appId = intval($_POST['application_id']);
    if(isset($_POST['approve'])){
        $appObj->updateStatus($appId, 'Approved');
    } elseif(isset($_POST['reject'])){
        $appObj->updateStatus($appId, 'Rejected');
    }
    header("Location: reviewapprove.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Review & Approval</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body class="bg-slate-50 text-slate-800">

<?php include_once __DIR__ . '/nav-admin.php'; ?>

<div class="max-w-7xl mx-auto px-6 mt-8">
    <h2 class="text-2xl font-bold mb-4">Applications List</h2>
    <?php if(empty($applications)): ?>
        <p>No applications submitted yet.</p>
    <?php else: ?>
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Student Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Scholarship</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Uploaded File</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Applied At</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach($applications as $index => $app): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 align-top"><?= $index + 1 ?></td>
                            <td class="px-4 py-3 align-top"><?= htmlspecialchars($app['firstName'] . " " . $app['lastName']) ?></td>
                            <td class="px-4 py-3 align-top"><?= htmlspecialchars($app['scholarship_title']) ?></td>
                            <td class="px-4 py-3 align-top">
                                <?php if($app['upload_file']): ?>
                                    <a class="text-brand-blue hover:underline" href="<?= htmlspecialchars($app['upload_file']) ?>" target="_blank">View</a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 align-top">
                                <?php $status = strtolower($app['status']); ?>
                                <span class="font-semibold <?php if($status==='pending') echo 'text-orange-500'; elseif($status==='approved') echo 'text-green-600'; elseif($status==='rejected') echo 'text-red-600'; ?>">
                                    <?= htmlspecialchars($app['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 align-top"><?= htmlspecialchars($app['applied_at']) ?></td>
                            <td class="px-4 py-3 align-top">
                                <?php if($app['status'] === 'Pending'): ?>
                                    <form method="post" class="inline-flex items-center gap-2">
                                        <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                        <button name="approve" class="inline-flex px-3 py-1.5 rounded-md bg-brand-green text-white hover:bg-green-600 text-sm">Approve</button>
                                        <button name="reject" class="inline-flex px-3 py-1.5 rounded-md bg-red-600 text-white hover:bg-red-700 text-sm">Reject</button>
                                    </form>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
<script src="scripts.min.js"></script>
</html>
