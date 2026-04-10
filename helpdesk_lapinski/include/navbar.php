<?php
session_start();

require_once 'config/config.php';

$stmt = mysqli_query($link, "SELECT * FROM users ");

$stmt = mysqli_query($link, "SELECT * FROM tickets ");
$ticket = $stmt->fetch_all(MYSQLI_ASSOC);

$stmt = mysqli_query($link, "SELECT perm FROM users where id = ".$_SESSION['id_users']);
$perm = mysqli_fetch_assoc($stmt);

$display ="";
if($perm['perm'] == "admin"){
    $display = "block";
}
else if($perm['perm'] == "technicien"){
    $display = "block";
}
else{
    $display = "none";
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
</head>

<style>
/* Navbar */
.container {
  background: #833AB4;
  background: linear-gradient(90deg,rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 0%, rgba(252, 176, 69, 1) 100%);
  border: 0.5px solid rgba(0, 0, 0, 0.1);
  border-radius: 12px;
  padding: 1.25rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 2rem;
  font-family: 'DM Sans', sans-serif;
  
}

.container h1 {
  font-size: 17px;
  font-weight: 600;
  color: #1a1a1a;
  letter-spacing: -0.3px;
  margin: 0 0 2px 0;
}

.container > p {
  font-size: 12px;
  color: #000000;
  margin: 0;
}

.container ul {
  display: flex;
  align-items: center;
  gap: 4px;
  list-style: none;
  margin: 0;
  padding: 0;
}

.container ul li a {
  display: inline-block;
  padding: 7px 14px;
  font-size: 15px;
  font-weight: 500;
  color: #000000;
  text-decoration: none;
  border-radius: 8px;
  border: 0.5px solid transparent;
  transition: all 0.15s ease;
  white-space: nowrap;
}

.container ul li a:hover {
  background: #833AB4;
  background: linear-gradient(90deg,rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 0%, rgba(252, 176, 69, 1) 100%);
  border-color: rgba(0, 0, 0, 0.08);
  color: #111;
}
s
.container ul li:last-child a {
  color: #ff0000;
  border-color: rgba(0, 0, 0, 0.08);
}

.container ul li:last-child a:hover {
  background: #833AB4;
  background: linear-gradient(90deg,rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 0%, rgba(252, 176, 69, 1) 100%);
  border-color: #f09595;
  color: #791f1f;
}

.container ul li.perm {
  display: inline-block;
  padding: 7px 14px;
  font-size: 15px;
  font-weight: 500;
  color: #0400ff;
  text-decoration: none;
  border-radius: 8px;
  border: 0.5px solid transparent;
  transition: all 0.15s ease;
  white-space: nowrap;
  cursor: crosshair;
}

</style>
<body>
    <!-- Navbar -->
    <div class="container">
        <h1>Helpdesk - Lapinski</h1>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="ticket.php" style="display: <?= $display ?>">Tickets</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li class="perm">Permission : <?= $perm['perm'] ?> </li>
        </ul>
    </div>
</body>
</html>