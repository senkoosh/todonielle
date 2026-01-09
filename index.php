<?php
// ---------- SIMPLE CONFIG ----------
// This is a beginner-friendly demo (PHP + MySQL)
// You can split files later if needed

$host = "localhost";
$user = "root";
$pass = "";
$db   = "hirono_app";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB connection failed");
}

// ---------- ADD TASK ----------
if (isset($_POST['task'])) {
    $task = $conn->real_escape_string($_POST['task']);
    $conn->query("INSERT INTO tasks (title, is_done) VALUES ('$task', 0)");
}

// ---------- TOGGLE TASK ----------
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $conn->query("UPDATE tasks SET is_done = NOT is_done WHERE id = $id");
}

$tasks = $conn->query("SELECT * FROM tasks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hirono Matcha Checklist</title>
<style>
:root{
  --matcha:#A8C686;
  --cream:#FAF7F2;
  --sage:#C9D8C5;
}
body{
  margin:0;
  font-family: 'Segoe UI', sans-serif;
  background:var(--cream);
}
.app{
  max-width:420px;
  margin:40px auto;
  background:white;
  border-radius:20px;
  padding:20px;
  position:relative;
}

.memo{
  background:var(--sage);
  padding:12px 16px;
  border-radius:14px;
  margin-bottom:10px;
  display:flex;
  align-items:center;
  justify-content:space-between;
}
.memo.done span{
  text-decoration: line-through;
  opacity:0.6;
}

.add-btn{
  position:absolute;
  bottom:20px;
  right:20px;
  width:56px;
  height:56px;
  border-radius:50%;
  background:var(--matcha);
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:28px;
  cursor:pointer;
}

.mood-overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.4);
  display:flex;
  align-items:center;
  justify-content:center;
}
.mood-box{
  background:white;
  padding:20px;
  border-radius:18px;
  text-align:center;
}
.moods span{
  font-size:32px;
  margin:8px;
  cursor:pointer;
}

.notif{
  background:var(--matcha);
  padding:10px;
  border-radius:10px;
  margin-top:10px;
  text-align:center;
  display:none;
}
</style>
</head>
<body>

<!-- MOOD CHECK -->
<div class="mood-overlay" id="mood">
  <div class="mood-box">
    <h3>What’s your mood today?</h3>
    <div class="moods">
      <span onclick="closeMood('😊')">😊</span>
      <span onclick="closeMood('😐')">😐</span>
      <span onclick="closeMood('😢')">😢</span>
      <span onclick="closeMood('😠')">😠</span>
    </div>
    <small>(Hirono faces here 🧸)</small>
  </div>
</div>

<div class="app">
  <h2>🍵 Matcha Tasks</h2>

  <?php while($row = $tasks->fetch_assoc()): ?>
    <div class="memo <?= $row['is_done'] ? 'done':'' ?>">
      <span><?= htmlspecialchars($row['title']) ?></span>
      <a href="?toggle=<?= $row['id'] ?>">✔</a>
    </div>
  <?php endwhile; ?>

  <div class="notif" id="notif">Hirono is cheering for u ✨</div>

  <form method="POST">
    <input name="task" placeholder="New task" required style="width:100%;padding:10px;border-radius:10px;border:1px solid #ddd;">
  </form>

  <!-- HIRONO ICON INSTEAD OF + -->
  <div class="add-btn" onclick="alert('Add a task below!')">🧸</div>
</div>

<script>
function closeMood(mood){
  document.getElementById('mood').style.display='none';
  document.getElementById('notif').innerText = `Hirono feels ${mood} with you`;
  document.getElementById('notif').style.display='block';
}
</script>

</body>
</html>

/* ---------- DATABASE ----------
CREATE DATABASE hirono_app;
USE hirono_app;
CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  is_done BOOLEAN
);
*/