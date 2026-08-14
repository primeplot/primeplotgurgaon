<?php
session_start();
require_once __DIR__ . '/../db-config.php';

if (empty($_SESSION['pg_admin_logged_in'])) {
  header('Location: login.php');
  exit;
}

$conn = get_db_connection();

/* ---- Handle assign/status update ---- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lead_id'])) {
  $id = (int) $_POST['lead_id'];
  $assigned_to = $_POST['assigned_to'] ?? 'Unassigned';
  $status = $_POST['status'] ?? 'New';
  $notes = trim($_POST['notes'] ?? '');

  $allowed_assign = ['Unassigned', 'Marketing', 'Sales', 'Other'];
  $allowed_status = ['New', 'Contacted', 'Site Visit Scheduled', 'Closed', 'Not Interested'];
  if (!in_array($assigned_to, $allowed_assign)) $assigned_to = 'Unassigned';
  if (!in_array($status, $allowed_status)) $status = 'New';

  $stmt = $conn->prepare("UPDATE leads SET assigned_to = ?, status = ?, notes = ? WHERE id = ?");
  $stmt->bind_param('sssi', $assigned_to, $status, $notes, $id);
  $stmt->execute();
  $stmt->close();

  header('Location: index.php' . (isset($_GET['filter']) ? '?filter=' . urlencode($_GET['filter']) : ''));
  exit;
}

/* ---- Filter ---- */
$filter = $_GET['filter'] ?? 'all';
$where = '';
if (in_array($filter, ['Marketing', 'Sales', 'Other', 'Unassigned'])) {
  $where = "WHERE assigned_to = '" . $conn->real_escape_string($filter) . "'";
}

$result = $conn->query("SELECT * FROM leads $where ORDER BY created_at DESC");

/* ---- Stats ---- */
$stats = $conn->query("SELECT assigned_to, COUNT(*) as c FROM leads GROUP BY assigned_to");
$counts = ['Unassigned' => 0, 'Marketing' => 0, 'Sales' => 0, 'Other' => 0];
while ($row = $stats->fetch_assoc()) { $counts[$row['assigned_to']] = (int)$row['c']; }
$total = array_sum($counts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Leads Dashboard — Prime Plot Gurgaon CRM</title>
<meta name="robots" content="noindex, nofollow" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<style>
  *{ box-sizing:border-box; }
  body{ font-family:Arial,sans-serif; background:#F5F3EA; margin:0; color:#1F2421; }
  .topbar{ background:#0B3D2E; color:#fff; padding:16px 24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; }
  .topbar h1{ font-size:18px; margin:0; }
  .topbar a{ color:#fff; text-decoration:none; background:rgba(255,255,255,.15); padding:8px 16px; border-radius:8px; font-size:13px; }
  .container{ max-width:1300px; margin:0 auto; padding:24px; }
  .stats{ display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:24px; }
  @media (max-width:800px){ .stats{ grid-template-columns:repeat(2,1fr); } }
  .stat-card{ background:#fff; border-radius:10px; padding:18px; text-align:center; box-shadow:0 2px 8px rgba(0,0,0,.06); text-decoration:none; color:inherit; border:2px solid transparent; }
  .stat-card.active{ border-color:#0B3D2E; }
  .stat-card .num{ font-size:26px; font-weight:800; }
  .stat-card .lbl{ font-size:12px; color:#5B6560; text-transform:uppercase; letter-spacing:.04em; }
  table{ width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.06); }
  th, td{ padding:12px 14px; text-align:left; font-size:13.5px; border-bottom:1px solid #EEE; vertical-align:top; }
  th{ background:#0B3D2E; color:#fff; font-size:12px; text-transform:uppercase; letter-spacing:.03em; }
  select, textarea{ font-size:12.5px; padding:6px; border-radius:6px; border:1px solid #ccc; width:100%; }
  textarea{ min-height:40px; resize:vertical; }
  .save-btn{ background:#C9A227; border:none; padding:6px 12px; border-radius:6px; font-weight:700; font-size:12px; cursor:pointer; margin-top:6px; }
  .badge{ display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; }
  .b-new{ background:#DCEBFF; color:#1746A2; }
  .b-contacted{ background:#FFF3D6; color:#8A6D00; }
  .b-visit{ background:#E4F6EA; color:#1E8E3E; }
  .b-closed{ background:#E4E4E4; color:#555; }
  .b-notinterested{ background:#FBE0E0; color:#B23A3A; }
  .empty{ text-align:center; padding:40px; color:#888; }
</style>
</head>
<body>

<div class="topbar">
  <h1>📋 Prime Plot Gurgaon — Leads Dashboard</h1>
  <a href="logout.php">Logout</a>
</div>

<div class="container">

  <div class="stats">
    <a href="?filter=all" class="stat-card <?php echo $filter==='all'?'active':''; ?>">
      <div class="num"><?php echo $total; ?></div><div class="lbl">All Leads</div>
    </a>
    <a href="?filter=Unassigned" class="stat-card <?php echo $filter==='Unassigned'?'active':''; ?>">
      <div class="num"><?php echo $counts['Unassigned']; ?></div><div class="lbl">Unassigned</div>
    </a>
    <a href="?filter=Marketing" class="stat-card <?php echo $filter==='Marketing'?'active':''; ?>">
      <div class="num"><?php echo $counts['Marketing']; ?></div><div class="lbl">Marketing</div>
    </a>
    <a href="?filter=Sales" class="stat-card <?php echo $filter==='Sales'?'active':''; ?>">
      <div class="num"><?php echo $counts['Sales']; ?></div><div class="lbl">Sales</div>
    </a>
    <a href="?filter=Other" class="stat-card <?php echo $filter==='Other'?'active':''; ?>">
      <div class="num"><?php echo $counts['Other']; ?></div><div class="lbl">Other</div>
    </a>
  </div>

  <table>
    <tr>
      <th>Date</th>
      <th>Name</th>
      <th>Mobile</th>
      <th>Email</th>
      <th>Plot Size</th>
      <th>Message</th>
      <th>Source</th>
      <th>Assign To / Status / Notes</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($lead = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo date('d M Y, h:i A', strtotime($lead['created_at'])); ?></td>
          <td><strong><?php echo htmlspecialchars($lead['full_name']); ?></strong></td>
          <td><?php echo htmlspecialchars($lead['mobile']); ?></td>
          <td><?php echo htmlspecialchars($lead['email']); ?></td>
          <td><?php echo htmlspecialchars($lead['plot_size']); ?></td>
          <td style="max-width:200px;"><?php echo nl2br(htmlspecialchars($lead['message'])); ?></td>
          <td><?php echo htmlspecialchars($lead['source']); ?></td>
          <td style="min-width:220px;">
            <form method="POST">
              <input type="hidden" name="lead_id" value="<?php echo $lead['id']; ?>" />
              <label style="font-size:11px;font-weight:700;">Assign to:</label>
              <select name="assigned_to">
                <?php foreach (['Unassigned','Marketing','Sales','Other'] as $opt): ?>
                  <option value="<?php echo $opt; ?>" <?php echo $lead['assigned_to']===$opt?'selected':''; ?>><?php echo $opt; ?></option>
                <?php endforeach; ?>
              </select>
              <label style="font-size:11px;font-weight:700;">Status:</label>
              <select name="status">
                <?php foreach (['New','Contacted','Site Visit Scheduled','Closed','Not Interested'] as $opt): ?>
                  <option value="<?php echo $opt; ?>" <?php echo $lead['status']===$opt?'selected':''; ?>><?php echo $opt; ?></option>
                <?php endforeach; ?>
              </select>
              <label style="font-size:11px;font-weight:700;">Notes:</label>
              <textarea name="notes" placeholder="Internal notes..."><?php echo htmlspecialchars($lead['notes'] ?? ''); ?></textarea>
              <button type="submit" class="save-btn">Save</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="8" class="empty">No leads yet. Submissions from your website forms will appear here.</td></tr>
    <?php endif; ?>
  </table>

</div>
</body>
</html>
