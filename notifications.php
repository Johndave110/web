<?php
session_start();
require_once "classes/Applications.php";

// Ensure student is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student'){
    header("Location: login.php");
    exit();
}

$appObj = new Application();
$student_id = $_SESSION['user_id'];

// Get all applications for this student
$applications = $appObj->getApplicationsByStudent($student_id);

// Filter notifications: show only approved/rejected applications
$notifications = array_filter($applications, function($app){
    return in_array($app['status'], ['Approved', 'Rejected']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notifications</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body class="bg-slate-50 text-slate-800">

<?php include_once __DIR__ . '/nav-student.php'; ?>

<div class="max-w-7xl mx-auto px-6 mt-8">
    <h2 class="text-2xl font-bold mb-4">Notifications</h2>

    <?php if(empty($notifications)): ?>
        <p>No notifications at this time.</p>
    <?php else: ?>
                <?php foreach($notifications as $notif): ?>
                    <?php $s = strtolower($notif['status']); ?>
                    <div class="rounded-xl bg-white p-4 shadow border-l-4 <?php if($s==='approved') echo 'border-green-600'; elseif($s==='rejected') echo 'border-red-600'; ?>">
                        <p>
                            Your application for <span class="font-semibold"><?= htmlspecialchars($notif['scholarship_title']) ?></span>
                            has been <span class="font-semibold <?= $s==='approved' ? 'text-green-600' : 'text-red-600' ?>"><?= htmlspecialchars($notif['status']) ?></span>
                            on <?= htmlspecialchars($notif['applied_at']) ?>.
                        </p>
                    </div>
                <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
<script src="scripts.min.js"></script>
</html>
