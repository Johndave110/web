<?php
// Legacy path shim: if someone hits /global/index.php, redirect to root index
header("Location: /webdev-2-Scholarship/index.php", true, 301);
exit;
?>