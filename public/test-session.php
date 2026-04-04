<?php
// Simple session test
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session Status: " . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive') . "<br>";
echo "Session Save Path: " . session_save_path() . "<br>";
echo "Session is writable: " . (is_writable(session_save_path()) ? 'Yes' : 'No') . "<br>";

// Test write to session
$_SESSION['test'] = 'Laravel Session Working';
echo "Test value written to session: " . $_SESSION['test'] . "<br>";
echo "<br>If you see this, PHP sessions are working correctly.";
