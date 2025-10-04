<?php
session_start();
$db = new PDO('sqlite:'.__DIR__.'/demo.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// If already logged in, redirect to dashboard
if (!empty($_SESSION['user'])) {
    header("Location: /dashboard.php"); exit;
}

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    // VULNERABLE: direct interpolation (SQLi demonstration)
    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass' LIMIT 1";
    try {
        $res = $db->query($sql);
        $row = $res ? $res->fetch(PDO::FETCH_ASSOC) : false;
        if ($row) {
            // set a simple session and redirect
            $_SESSION['user'] = $row['username'];
            $_SESSION['role'] = ($row['username'] === 'admin') ? 'admin' : 'user';
            header("Location: /dashboard.php"); exit;
        } else {
            $msg = "Login failed";
        }
    } catch (Exception $e) {
        $msg = "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>page1 - vulnerable</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <div class="logo">Parspec — Test App</div>
      <div class="lead">This is <strong>page1</strong>. Intentionally vulnerable to SQL injection for testing.</div>
      <div class="card">
        <h2>Login (Vulnerable)</h2>
        <form method="post">
          <label>Username
            <input type="text" name="username" placeholder="enter username (e.g. admin)">
          </label>
          <label>Password
            <input type="password" name="password" placeholder="password">
          </label>
          <button type="submit">Login</button>
        </form>
        <p class="small"><?php echo htmlspecialchars($msg); ?></p>
      </div>
      <div class="footer small">Tip: try SQLi payload <code>' OR '1'='1' --</code> in username</div>
    </div>
    <div class="right">
      <div class="notice card">
        <strong>Vulnerable by design.</strong><br>
        Used for demonstrating SQL injection bypasses. Do not reuse this code.
      </div>
      <div class="notice card">
        <strong>Proof of concept:</strong><br>
        If exploit succeeds you will be redirected to a dashboard that shows your role (admin/user).
      </div>
    </div>
  </div>
</body>
</html>
