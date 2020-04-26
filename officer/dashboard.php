<?php
session_start();
if(!isset($_SESSION['id_officer'])){
  header("Location: login.php");
}else if(isset($_SESSION['id_applicant'])){
  header("Location: ../dashboard.php");
  die();
}else{
  require_once("../main.php");
  $sql = new SQL();
  $bind[':id'] = $_SESSION['id_user'];
  $bio = $sql->run("SELECT u.fullname as name, a.userID FROM user as u
  INNER JOIN housingofficer as a ON a.userID = u.userID
  WHERE u.userID = :id", $bind);
  die('
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Dashboard | Kosan</title>
  <link rel="icon" href="image/icon.svg">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/kosan.css">
  <link href="../fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
  <script src="../js/jquery-3.4.1.min.js" type="text/javascript"></script>
  <script src="../js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
  <div class="container mt-5">
    <div class="col-md-10 rounded border shadow-sm py-3 mx-auto row px-0 bg-white">
      <div class="col-auto border-right p-0">
        <ul class="nav" style="display: block;">
          <li class="nav-item">
            <a class="nav-link l l-active" href="dashboard.php" alt="Dashboard" style="/*! border-bottom: 1px solid; */">
              <span class="fa fa-home"></span>
              <small>Home</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l" href="residence.php" alt="Residence">
              <span class="fa fa-city"></span>
              <small>Residence</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l" href="application.php" alt="Application">
              <span class="fa fa-list"></span>
              <small>Application</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l" href="profile.php" alt="Profile">
              <span class="fa fa-user"></span>
              <small>User</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l" href="logout.php" alt="Log out" style="border: 0;">
              <span class="fa fa-sign-out-alt" style="width: 100%;"></span>
              <small>Log Out</small>
            </a>
          </li>
        </ul>
      </div>
      <div class="col">
        <div class="row">
          <div class="col">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb ml-2 bg-white px-3 py-2 border mb-3">
                <li class="breadcrumb-item active" aria-current="page"><a href="dashboard.php">Dashboard</a></li>
              </ol>
            </nav>
          </div>
          <div class="col">
            <p class="text-right">'.ucwords($bio[0]['name']).'</p>
          </div>
        </div>
        <div class="content p-2">
          <div class="col-lg-12 rounded mr-3 mt-3">
            <h3>My Profile</h3>
            <div class="border rounded p-2 mb-3">
              <table class="table table-hover table-striped table-sm">
                <tr>
                  <td><b>Name:</b></td>
                  <td>'.ucwords($bio[0]['name']).'</td>
                </tr>
              </table>
            </div>
            <div class="d-flex">
              <a href="profile.php" class="d-block ml-auto"><button class="btn btn-sm btn-primary">Edit Profile</button></a>
            </div>
          </div>
        </div>
        <div class="bottom bg-secondary p-2 rounded">
        </div>
      </div>
    </div>
  </div>
</body>
</html>
');
}
?>
