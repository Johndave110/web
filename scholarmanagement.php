<?php
session_start();
// Admin check - protect this page
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
  header("Location: login.php");
  exit();
}

require_once "classes/Scholarship.php";
$scholarObj = new Scholarship();

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$scholarships = $scholarObj->getScholarships($limit, $offset);
$total = $scholarObj->countScholarships();
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scholarship Management</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { blue: '#1e40af', green: '#16a34a', dark: '#0f172a' } } } } };</script>
</head>
<body class="bg-slate-50 text-slate-800">

<?php include_once __DIR__ . '/nav-admin.php'; ?>

<div class="max-w-7xl mx-auto px-6 mt-8">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">Scholarship Management</h2>
    <a href="addscholarship.php" class="inline-flex items-center px-4 py-2 rounded-md bg-brand-green text-white hover:bg-green-600">+ Add Scholarship</a>
  </div>

  <div class="overflow-x-auto bg-white rounded-xl shadow">
    <table class="min-w-full divide-y divide-slate-200">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Title</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Description</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Requirements</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Deadline</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Total Slots</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Available Slots</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Min GPA</th>
          <th class="px-4 py-3 text-left text-xs font-medium text-slate-600">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-200">
        <?php foreach($scholarships as $sch): ?>
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 align-top"><?= htmlspecialchars($sch['title']) ?></td>
          <td class="px-4 py-3 align-top"><?= htmlspecialchars($sch['description']) ?></td>
          <td class="px-4 py-3 align-top"><?= htmlspecialchars($sch['requirements']) ?></td>
          <td class="px-4 py-3 align-top"><?= htmlspecialchars($sch['deadline']) ?></td>
          <td class="px-4 py-3 align-top"><?= htmlspecialchars($sch['total_slots']) ?></td>
          <td class="px-4 py-3 align-top"><?= htmlspecialchars($sch['available_slots']) ?></td>
          <td class="px-4 py-3 align-top"><?= htmlspecialchars($sch['min_gpa']) ?></td>
          <td class="px-4 py-3 align-top space-x-2">
            <a class="text-brand-blue hover:underline" href="editscholarship.php?id=<?= $sch['scholarship_id'] ?>">Edit</a>
            <a class="text-red-600 hover:underline" href="deletescholarship.php?id=<?= $sch['scholarship_id'] ?>" onclick="return confirm('Delete this scholarship?')">Delete</a>
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
