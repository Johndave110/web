<?php
session_start();
require_once "classes/Profile.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$profileObj = new Profile();
$profile = $profileObj->viewProfile($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body>

<!-- ✅ Shared Navigation -->
<nav class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
    <div class="flex items-center gap-2">
      <img class="h-8 w-8" src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" alt="Scholarship Icon">
      <span class="font-semibold text-brand-blue">Scholarship Portal</span>
    </div>
    <div class="hidden md:flex items-center gap-6">
      <a href="studash.php" class="text-slate-700 hover:text-brand-blue">Dashboard</a>
      <a href="browsescholarships.php" class="text-slate-700 hover:text-brand-blue">Browse Scholarships</a>
      <a href="tracking.php" class="text-slate-700 hover:text-brand-blue">Tracking</a>
      <a href="notifications.php" class="text-slate-700 hover:text-brand-blue">Notifications</a>
      <a href="studentprofile.php" class="text-brand-blue font-medium">Profile</a>
    </div>
    <a href="logout.php" class="inline-flex px-3 py-2 rounded-md bg-brand-blue text-white hover:bg-blue-700">Logout</a>
  </div>
</nav>

<!-- ✅ Main Profile Section -->
<div class="max-w-3xl mx-auto px-6 mt-8">
  <h2 class="text-2xl font-bold mb-4">My Profile</h2>

  <?php if ($profile): ?>
    <div class="bg-white rounded-xl shadow p-6 space-y-2">
      <p><span class="font-semibold">First Name:</span> <?= htmlspecialchars($profile['firstName']) ?></p>
      <p><span class="font-semibold">Last Name:</span> <?= htmlspecialchars($profile['lastName']) ?></p>
      <p><span class="font-semibold">Middle Name:</span> <?= htmlspecialchars($profile['middleName'] ?? '') ?></p>
      <p><span class="font-semibold">Birthdate:</span> <?= htmlspecialchars($profile['birthdate']) ?></p>
      <p><span class="font-semibold">Address:</span> <?= htmlspecialchars($profile['address']) ?></p>
      <p><span class="font-semibold">Contact Number:</span> <?= htmlspecialchars($profile['contactNumber']) ?></p>
      <p><span class="font-semibold">GPA:</span> <?= htmlspecialchars($profile['gpa']) ?></p>
      <p><span class="font-semibold">Family Income:</span> <?= htmlspecialchars($profile['familyIncome']) ?></p>
      <p><span class="font-semibold">School:</span> <?= htmlspecialchars($profile['school']) ?></p>
      <p><span class="font-semibold">Course:</span> <?= htmlspecialchars($profile['course']) ?></p>
      <p><span class="font-semibold">Year Level:</span> <?= htmlspecialchars($profile['yearLevel']) ?></p>
    </div>
    <div class="mt-4 flex items-center gap-3">
      <a href="edit_profile.php" class="inline-flex items-center px-5 py-2.5 rounded-md bg-brand-blue text-white hover:bg-blue-700">Edit Profile</a>
      <a href="studash.php" class="inline-flex items-center px-5 py-2.5 rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Back to Dashboard</a>
    </div>
  <?php else: ?>
    <p>No profile found.</p>
  <?php endif; ?>
</div>

</body>
<script src="scripts.min.js"></script>
</html>