<?php
session_start();
if(isset($_SESSION['id_applicant'])){
  header("Location: ../dashboard.php");
  die();
}else if(!empty($_POST)){
  if(isset($_POST['address'], $_POST['size'], $_POST['unit'], $_POST['monthly'])
    && !empty($_POST['address']) && !empty($_POST['size']) && !empty($_POST['unit']) && !empty($_POST['monthly'])){
      require_once("../main.php");
      $sql = new SQL();
      $bind[':i'] = $_SESSION['id_officer'];
      $bind[':a'] = $_POST['address'];
      $bind[':s'] = $_POST['size'];
      $bind[':u'] = $_POST['unit'];
      $bind[':m'] = $_POST['monthly'];
      $residenceID = $sql->run("INSERT INTO residence VALUES('', :i, :a, :u, :s, :m)", $bind);
      unset($bind);
      $bind[':i'] = $residenceID;
      for($i=0; $i<intval($_POST['unit']); $i++){
        $sql->run("INSERT INTO unit VALUES('', :i, 'available')", $bind);
      }
      header("Location: residence.php");
      die();
  }else{
    $_SESSION['error'] = 'empty';
    header("Location: setup.php");
    die();
  }
}else{
  require_once("../main.php");
  $sql = new SQL();
  $bind[':id'] = $_SESSION['id_user'];
  $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
  die('
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Set Up New Residence | Kosan</title>
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
            <a class="nav-link l" href="dashboard.php" alt="Dashboard" style="/*! border-bottom: 1px solid; */">
              <span class="fa fa-home"></span>
              <small>Home</small>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link l l-active" href="residence.php" alt="Residence">
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
                <li class="breadcrumb-item active" aria-current="page"><a href="residence.php">Residence</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Edit Residence</a></li>
              </ol>
            </nav>
          </div>
          <div class="col">
            '.((isset($_SESSION['id_user'])) ? '<p class="text-right">'.ucwords($bio[0]['name'])."</p>" : $account).'
          </div>
        </div>
        <div class="content p-2">
          <h3>Setup New Residence</h3>
          <form class="col-md-6 p-0" method="POST">
            <div class="form-group">
              <label>Address</label>
              <textarea class="form-control" name="address" required></textarea>
            </div>
            <div class="d-md-flex">
              <div class="form-group col-md-6 p-0 pr-2">
                <label>Unit Size</label>
                <input type="number" class="form-control" name="size" required>
              </div>
              <div class="form-group col-md-6 p-0">
                <label>Number of Available</label>
                <input type="number" class="form-control" name="unit" required>
              </div>
            </div>
            <div class="form-group">
              <label>Monthly Rental</label>
              <input type="number" class="form-control" name="monthly" required>
            </div>
            <button class="btn btn-sm btn-primary d-block ml-auto">Set Up New Residence</button>
          </form>
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
