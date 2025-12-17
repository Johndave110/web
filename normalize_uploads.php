<?php
// One-time script to normalize legacy upload_file paths in the DB
// Usage: visit via browser or run `php normalize_uploads.php`

require_once __DIR__ . '/classes/Database.php';

$db = new Database();
$pdo = $db->connect();

// Set error mode for visibility
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$updates = [
    // Strip '../' before uploads path
    [
        'sql' => "UPDATE applications SET upload_file = REPLACE(upload_file, '../uploads/applications/', 'uploads/applications/') WHERE upload_file LIKE '../uploads/applications/%'",
        'label' => "Strip ../ prefix"
    ],
    // Handle historical absolute-like prefixes
    [
        'sql' => "UPDATE applications SET upload_file = REPLACE(upload_file, '/webdev-2-Scholarship/uploads/applications/', 'uploads/applications/') WHERE upload_file LIKE '/webdev-2-Scholarship/uploads/applications/%'",
        'label' => "Replace /webdev-2-Scholarship prefix"
    ],
    [
        'sql' => "UPDATE applications SET upload_file = REPLACE(upload_file, 'webdev-2-Scholarship/uploads/applications/', 'uploads/applications/') WHERE upload_file LIKE 'webdev-2-Scholarship/uploads/applications/%'",
        'label' => "Replace webdev-2-Scholarship prefix (no leading slash)"
    ],
    // Generic: if uploads/applications exists anywhere inside, trim to start there
    // This uses MySQL SUBSTRING/LOCATE logic
    [
        'sql' => "UPDATE applications SET upload_file = SUBSTRING(upload_file, LOCATE('uploads/applications/', upload_file)) WHERE upload_file LIKE '%uploads/applications/%' AND upload_file NOT LIKE 'uploads/applications/%'",
        'label' => "Trim to uploads/applications start"
    ],
];

$results = [];
foreach ($updates as $u) {
    $count = $pdo->exec($u['sql']);
    $results[] = [$u['label'], $count === false ? 0 : $count];
}

header('Content-Type: text/plain');
echo "Upload path normalization complete.\n\n";
foreach ($results as [$label, $count]) {
    echo sprintf("- %s: %d rows\n", $label, $count);
}

// Show a few normalized samples (if any)
$stmt = $pdo->query("SELECT application_id, upload_file FROM applications WHERE upload_file LIKE 'uploads/applications/%' ORDER BY application_id DESC LIMIT 5");
$rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
if ($rows) {
    echo "\nSample normalized rows:\n";
    foreach ($rows as $r) {
        echo "  #{$r['application_id']}: {$r['upload_file']}\n";
    }
}

echo "\nDone.\n";
?>
