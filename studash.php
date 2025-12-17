<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body>

<?php include_once __DIR__ . '/nav-student.php'; ?>

<!-- Page content would start here -->
<div class="max-w-7xl mx-auto px-6 mt-8">
    <h2 class="text-2xl font-bold mb-4">Student Overview</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-xl bg-white p-6 shadow cursor-pointer" onclick="location.href='browsescholarships.php'">
            <h3 class="text-lg font-semibold mb-2">Scholarships</h3>
            <p class="text-slate-600">Click to view all scholarships</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow cursor-pointer" onclick="location.href='tracking.php'">
            <h3 class="text-lg font-semibold mb-2">My Applications</h3>
            <p class="text-slate-600">Click to track your applications</p>
        </div>
        <div class="rounded-xl bg-white p-6 shadow cursor-pointer" onclick="location.href='notifications.php'">
            <h3 class="text-lg font-semibold mb-2">Notifications</h3>
            <p class="text-slate-600">Click to view all notifications</p>
        </div>
    </div>
</div>
</body>
<script src="scripts.min.js"></script>
</html>