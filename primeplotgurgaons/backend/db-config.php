<?php
/**
 * =========================================================
 * DATABASE CONFIG — fill these in with YOUR Hostinger MySQL details
 * Find/create these in: hPanel > Databases > MySQL Databases
 * =========================================================
 */
define('DB_HOST', 'localhost');              // Usually 'localhost' on Hostinger
define('DB_NAME', 'u208677131_primeplot');    // Your database name (Hostinger prefixes with u123456789_)
define('DB_USER', 'u208677131_primeplot');       // Your database username
define('DB_PASS', 'Gurgaon@789#');   // Your database password

/**
 * ADMIN LOGIN — change this password before going live!
 */
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'ChangeThisPassword123!');

function get_db_connection() {
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed.']));
  }
  $conn->set_charset('utf8mb4');
  return $conn;
}
