<?php
session_start();
require_once __DIR__ . '/../db-config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = $_POST['username'] ?? '';
  $p = $_POST['password'] ?? '';
  if ($u === ADMIN_USERNAME && $p === ADMIN_PASSWORD) {
    $_SESSION['pg_admin_logged_in'] = true;
    header('Location: index.php');
    exit;
  } else {
    $error = 'Invalid username or password.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Login — Prime Plot Gurgaon CRM</title>
<meta name="robots" content="noindex, nofollow" />
<style>
  body{ font-family:Arial,sans-serif; background:#0B3D2E; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
  .box{ background:#fff; padding:36px; border-radius:12px; width:100%; max-width:340px; box-shadow:0 10px 30px rgba(0,0,0,.25); }
  h1{ font-size:20px; margin-bottom:20px; color:#0B3D2E; }
  label{ display:block; font-size:13px; font-weight:600; margin:12px 0 4px; }
  input{ width:100%; padding:10px 12px; border:1px solid #ccc; border-radius:8px; box-sizing:border-box; font-size:14px; }
  button{ width:100%; margin-top:18px; background:#0B3D2E; color:#fff; padding:12px; border:none; border-radius:8px; font-weight:700; cursor:pointer; }
  .error{ color:#C0392B; font-size:13px; margin-top:10px; }
</style>
</head>
<body>
  <div class="box">
    <h1>🔒 Prime Plot Gurgaon — CRM Login</h1>
    <form method="POST">
      <label>Username</label>
      <input type="text" name="username" required autofocus />
      <label>Password</label>
      <input type="password" name="password" required />
      <button type="submit">Login</button>
      <?php if ($error) echo '<div class="error">' . htmlspecialchars($error) . '</div>'; ?>
    </form>
  </div>
</body>
</html>
