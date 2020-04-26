<?php
session_start();
if(isset($_SESSION['id_applicant'])){
  header("Location: ../dashboard.php");
  die();
}else{
  if(empty($_GET) || !isset($_GET['id'])){
    $_SESSION['error'] = 'id';
    header("Location: residence.php");
    die();
  }else{
    require_once("../main.php");
    $sql = new SQL();
    $bind[':id'] = $_GET['id'];
    $residence = $sql->run("SELECT * FROM residence WHERE residenceID = :id", $bind);
    if(empty($residence)){
      $_SESSION['error'] = 'id';
      header("Location: residence.php");
      die();
    }else{
      if(isset($_GET['remove'])){
        $sql->run("DELETE FROM application WHERE residenceID = :id;
          DELETE FROM unit WHERE residenceID = :id;
          DELETE FROM residence WHERE residenceID = :id", $bind);
          header("Location: residence.php");
          die();
      }else if(!empty($_POST)){
        $count = $sql->run("SELECT COUNT(*) as total FROM unit WHERE residenceID = :id", $bind);
        $u = intval($_POST['unit']);
        $t = intval($count[0]['total']);
        if($u !== $t){
          if($u>$t){
            for($i = $t; $i<$u; $i++ ){
              $sql->run("INSERT INTO unit VALUES('', :id, 'available')", $bind);
            }
          }else{
            $unit = $sql->run("SELECT * FROM unit WHERE residenceID = :id ORDER BY availability ASC", $bind);
            for($i = 0; $i<($t-$u); $i++){
              $bind[':id'] = $unit[$i]['unitNo'];
              $sql->run("DELETE FROM allocation WHERE unitNo = :id; DELETE FROM unit WHERE unitNo = :id", $bind);
            }
            $bind[':id'] = $_GET['id'];
          }
        }
        $bind[':a'] = $_POST['address'];
        $bind[':s'] = $_POST['size'];
        $bind[':u'] = $_POST['unit'];
        $bind[':m'] = $_POST['monthly'];
        $sql->run("UPDATE residence SET address = :a, numUnits = :u, sizePerUnit = :s, monthlyRental = :m WHERE residenceID = :id", $bind);
        header("Location: edit.php?id=".$_GET['id']);
        die();
      }else{
        $bind[':id'] = $_SESSION['id_user'];
        $bio = $sql->run("SELECT fullname as name FROM user WHERE userID = :id", $bind);
        die('<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
          <title>Edit Residence | Kosan</title>
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
                    </ol>
                  </nav>
                </div>
                <div class="col">
                  '.((isset($_SESSION['id_user'])) ? '<p class="text-right">'.ucwords($bio[0]['name'])."</p>" : $account).'
                </div>
              </div>
              <div class="content p-2">
                  <form class="col-md-6 p-0" method="POST">
                    <div class="form-group">
                      <label>Address</label>
                      <textarea class="form-control" name="address" required>'.$residence[0]['address'].'</textarea>
                    </div>
                    <div class="d-md-flex">
                      <div class="form-group col-md-6 p-0 pr-2">
                        <label>Unit Size</label>
                        <input type="number" class="form-control" name="size" placeholder="'.$residence[0]['sizePerUnit'].'" value="'.$residence[0]['sizePerUnit'].'" required>
                      </div>
                      <div class="form-group col-md-6 p-0">
                        <label>Number of Available</label>
                        <input type="number" class="form-control" name="unit" placeholder="'.$residence[0]['numUnits'].'" value="'.$residence[0]['numUnits'].'" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Monthly Rental</label>
                      <input type="number" class="form-control" name="monthly" placeholder="'.$residence[0]['monthlyRental'].'" value="'.$residence[0]['monthlyRental'].'" required>
                    </div>
                    <div class="d-flex">
                    <a href="application.php"><button type="button" class="btn btn-sm btn-confirm mr-2">Back</button></a>
                      <a class="d-block ml-auto mr-2" href="edit.php?id='.$_GET['id'].'&remove=1" onclick="return confirm(\'Are you sure?\')"><button class="btn btn-danger btn-sm" type="button">Remove</button></a>
                      <button class="btn btn-primary btn-sm" type="submit">Save Changes</button>
                    </div>
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
    }
  }
}
?>
