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
  $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
  die('<!DOCTYPE html>
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
    <div class="container-fluid col-md-11 row mx-auto mr-3 mt-3">
              <div class="col-sm-1"><img src="img/logo_dbkl.png" class="img-fluid" width="100"></div><div class="col-sm-11"><h1>MHS Kosan</h1></div></div>
           <div class="col-md-11 mx-auto mt-3">
        <nav class="breadcrumb">
    <a class="breadcrumb-item" href="index.php">MHSKosan</a>
    <a class="breadcrumb-item" href="#">Dashboard</a>
    </nav></div>
      <div class="col-md-10 border shadow-sm py-3 mx-auto row">
        <div class="col-md-1 border-right p-0">
          <ul class="nav d-md-flex">
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php" alt="Dashboard"><span class="l fa fa-home l-active"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="residence.php" alt="Residence"><span class="l fa fa-city"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="application.php" alt="Application"><span class="l fa fa-list"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="profile.php" alt="Profile"><span class="l fa fa-user"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php" title="Logout" alt="Log out" onclick="return confirm(\'Are you sure?\')"><span class="l fa fa-sign-out-alt"></span></a>
            </li>
          </ul>
        </div>
        <div class="col-md-11 pr-md-0">
        <div><span class="fa fa-user-circle"></span> <a class="text" href="profile.php">'.ucwords($bio[0]['name']).'</a></div>
          <div class="content p-2">
          <div class="col-lg-12 rounded mr-3 mt-3">
          <h2>Dashboard</h2>
          <h3>My Profile</h3>
            <table class="table table-hover table-striped table-sm border shadow-sm rounded">
              <tr>
                <td><b>Name:</b>
                <td>Fullname</td>
            </tr>
            <tr>
                <td><b>E-mail:</b></td>
                <td>Mail</td>
            </tr>
            </table>
            <div class="d-flex"><a href="profile.php" class="d-block ml-auto"><button class="btn btn-sm btn-primary">Edit Profile</button><a></div>
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
