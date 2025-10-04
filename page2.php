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
    $user = trim((string)($_POST['username'] ?? ''));
    $pass = trim((string)($_POST['password'] ?? ''));

    // Basic input sanity: limit length
    if (strlen($user) > 100 || strlen($pass) > 100) {
        $msg = "Invalid input";
    } else {
        // SECURE: prepared statement + exact match
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :u AND password = :p LIMIT 1");
        $stmt->execute([':u' => $user, ':p' => $pass]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $_SESSION['user'] = $row['username'];
            $_SESSION['role'] = ($row['username'] === 'admin') ? 'admin' : 'user';
            header("Location: /dashboard.php"); exit;
        } else {
            $msg = "Login failed";
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>page2 - secure</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <div class="logo">Parspec — Test App</div>
      <div class="lead">This is <strong>page2</strong>. Protected using prepared statements.</div>
      <div class="card">
        <h2>Login (Secure)</h2>
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
      <div class="footer small">This page uses parameterized queries and basic input checks to prevent SQL injection.</div>
    </div>
    <div class="right">
      <div class="notice card">
        <strong>Protected.</strong><br>
        Attempts like <code>' OR '1'='1' --</code> will not bypass authentication here.
      </div>
      <div class="notice card">
        <strong>Additional defense:</strong><br>
        Deploy ModSecurity rules for further WAF-level protection.
      </div>
    </div>
  </div>
</body>
</html>
