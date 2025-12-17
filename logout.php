<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to landing page at project root
header("Location: index.php");
exit();