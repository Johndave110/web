<?php
session_start();
require_once "classes/Applications.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student'){
    header("Location: login.php");
    exit();
}

$appObj = new Application();
$student_id = $_SESSION['user_id'];

$applications = $appObj->getApplicationsByStudent($student_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Application Tracking</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body class="bg-slate-50 text-slate-800">

<?php include_once __DIR__ . '/nav-student.php'; ?>

<div class="max-w-7xl mx-auto px-6 mt-8">
    <h2 class="text-2xl font-bold mb-4">My Applications</h2>

    <?php if(empty($applications)): ?>
        <p>You haven't applied to any scholarships yet.</p>
    <?php else: ?>
                <div class="overflow-x-auto bg-white rounded-xl shadow">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Scholarship</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Uploaded File</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Applied At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                <?php foreach($applications as $index => $app): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 align-top"><?= $index + 1 ?></td>
                                    <td class="px-4 py-3 align-top"><?= htmlspecialchars($app['scholarship_title']) ?></td>
                                    <td class="px-4 py-3 align-top">
                                        <?php $status = strtolower($app['status']); ?>
                                        <span class="font-semibold <?php if($status==='pending') echo 'text-orange-500'; elseif($status==='approved') echo 'text-green-600'; elseif($status==='rejected') echo 'text-red-600'; ?>">
                                            <?= htmlspecialchars($app['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <?php if($app['upload_file']): ?>
                                            <a href="<?= htmlspecialchars($app['upload_file']) ?>" target="_blank" class="text-brand-blue hover:underline">View</a>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 align-top"><?= htmlspecialchars($app['applied_at']) ?></td>
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
