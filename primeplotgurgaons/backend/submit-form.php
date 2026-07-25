<?php
/**
 * Receives POST data from all website forms (hero, bottom CTA, popup)
 * and stores each submission as a lead in the database.
 */
header('Content-Type: application/json');
require_once __DIR__ . '/db-config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'error' => 'Method not allowed']);
  exit;
}

function clean($v) {
  return trim(htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'));
}

$full_name = clean($_POST['full_name'] ?? '');
$mobile    = clean($_POST['mobile'] ?? '');
$email     = clean($_POST['email'] ?? '');
$plot_size = clean($_POST['plot_size'] ?? '');
$message   = clean($_POST['message'] ?? '');
$source    = clean($_POST['source'] ?? 'Website Form');

if ($full_name === '' || $mobile === '') {
  http_response_code(400);
  echo json_encode(['success' => false, 'error' => 'Name and Mobile are required.']);
  exit;
}

$conn = get_db_connection();

$stmt = $conn->prepare(
  "INSERT INTO leads (full_name, mobile, email, plot_size, message, source, assigned_to, status)
   VALUES (?, ?, ?, ?, ?, ?, 'Unassigned', 'New')"
);
$stmt->bind_param('ssssss', $full_name, $mobile, $email, $plot_size, $message, $source);

if ($stmt->execute()) {
  echo json_encode(['success' => true, 'lead_id' => $stmt->insert_id]);
} else {
  http_response_code(500);
  echo json_encode(['success' => false, 'error' => 'Could not save your submission.']);
}

$stmt->close();
$conn->close();
