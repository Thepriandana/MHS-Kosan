<?php
session_start();
if(!isset($_SESSION['id_applicant'])){
  header("Location: login.php");
}else if(isset($_SESSION['id_officer'])){
  header("Location: officer/dashboard.php");
  die();
}else if(!empty($_POST)){
  if(isset($_POST['name'], $_POST['email'], $_POST['mi'])){
    require_once("main.php");
    $sql = new SQL();
    $bind[':id'] = $_SESSION['id_user'];
    $bind[':n'] = $_POST['name'];
    $bind[':email'] = strtolower($_POST['email']);
    $bind[':mi'] = $_POST['mi'];
    $sql->run("UPDATE user SET fullname = :n WHERE userID = :id; UPDATE applicant SET email = :email, monthlyIncome = :mi WHERE userID = :id", $bind);
  }else{
    $_SESSION['error'] = "empty";
  }
  header("Location: profile.php");
  die();
}else{
  require_once("main.php");
  $sql = new SQL();
  $bind[':id'] = $_SESSION['id_user'];
  $bio = $sql->run("SELECT u.fullname as name, a.email, a.monthlyIncome as mi FROM user AS u INNER JOIN applicant AS a ON a.userID = u.userID WHERE u.userID = :id", $bind);
  $error = '';
  if(isset($_SESSION['error'])){
    if($_SESSION['error'] === "empty"){
      $error = "Please fill form correctly";
    }
    $error = '<span class="d-block alert alert-danger text-center w-100 p-1">'.$$error.'</span>';
    unset($_SESSION['error']);
  }

  die('<!DOCTYPE html>
  <html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Dashboard | Kosan</title>
    <link rel="icon" href="image/icon.svg">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/kosan.css">
    <link href="fontawesome-free-5.11.2-web/css/all.min.css" rel="stylesheet">
    <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
  </head>
  <body>
    <div class="container mt-5">
    <div class="container-fluid col-md-11 row mx-auto mr-3 mt-3">
              <div class="col-sm-1"><img src="img/logo_dbkl.png" class="img-fluid" width="100"></div><div class="col-sm-11"><h1>MHS Kosan</h1></div></div>
           <div class="col-md-11 mx-auto mt-3">
        <nav class="breadcrumb">
    <a class="breadcrumb-item" href="index.php">MHSKosan</a>
    <a class="breadcrumb-item" href="#">Edit Profile</a>
    </nav></div>
      <div class="col-md-10 border shadow-sm py-3 mx-auto row">
        <div class="col-md-1 border-right p-0">
          <ul class="nav d-md-flex">
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php" alt="Dashboard"><span class="l fa fa-home"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="residence.php" alt="Residence"><span class="l fa fa-city"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="application.php" alt="Application"><span class="l fa fa-list"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="profile.php" alt="Profile"><span class="l fa fa-user l-active"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php" title="Logout" alt="Log out" onclick="return confirm(\'Are you sure?\')"><span class="l fa fa-sign-out-alt"></span></a>
            </li>
          </ul>
        </div>
        <div class="col-md-11 pr-md-0">
          <div><span class="fa fa-user-circle"></span> <a class="text" href="profile.php">'.ucwords($bio[0]['name']).'</a></div>
          <div class="content p-2 mt-3">
          <h3>Edit Profile</h3>
            <div class="col-md-5 p-0">
            <p id="p_change"></p>
              <form method="POST">
                <div class="form-group">
                  <label>Full Name</label>
                  <input type="text" class="form-control" name="name" placeholder="'.ucwords($bio[0]['name']).'" value="'.ucwords($bio[0]['name']).'" required>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" name="email" placeholder="'.$bio[0]['email'].'" value="'.$bio[0]['email'].'" required>
                </div>
                <div class="form-group">
                  <label>Monthly Income</label>
                  <input type="number" min="1" max="500" class="form-control" name="mi" placeholder="'.$bio[0]['mi'].'" value="'.$bio[0]['mi'].'" required>
                </div>
                <div class="d-flex">
                  <a href="password.php" class="d-flex ml-auto mr-2 card-link"><button type="button" class="btn btn-sm btn-danger">Change Password</button></a>
                  <button type-"submit" class="btn btn-sm btn-primary">Save Profile</button>
                </div>
              </form>
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
