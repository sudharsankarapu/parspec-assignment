<?php
session_start();
if (empty($_SESSION['user'])) {
    header("Location: /page1.php"); exit;
}
$user = htmlspecialchars($_SESSION['user']);
$role = htmlspecialchars($_SESSION['role'] ?? 'user');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
  <div class="dashboard">
    <div class="d-header">
      <div>
        <div style="font-size:18px;font-weight:700">Welcome, <?php echo $user; ?></div>
        <div class="small">Role: <span class="role"><?php echo $role; ?></span></div>
      </div>
      <div>
        <form method="post" action="/logout.php" style="display:inline">
          <button class="logout" type="submit">Logout</button>
        </form>
      </div>
    </div>

    <div style="margin-top:18px">
      <?php if ($role === 'admin'): ?>
        <div style="font-weight:600;margin-bottom:8px">Admin panel</div>
        <div class="stat-grid">
          <div class="stat"><div style="font-size:20px">42</div><div class="small">Active Users</div></div>
          <div class="stat"><div style="font-size:20px">7</div><div class="small">Open Reports</div></div>
          <div class="stat"><div style="font-size:20px">1</div><div class="small">Critical Alerts</div></div>
        </div>
      <?php else: ?>
        <div class="small">You are logged in as a regular user. Your dashboard shows minimal info.</div>
        <div class="stat-grid" style="margin-top:12px">
          <div class="stat"><div style="font-size:20px">3</div><div class="small">Tasks</div></div>
          <div class="stat"><div style="font-size:20px">0</div><div class="small">Alerts</div></div>
          <div class="stat"><div style="font-size:20px">5</div><div class="small">Points</div></div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
