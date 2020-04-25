<?php
session_start();
if(isset($_SESSION['id_applicant'])){
  header("Location: dashboard.php");
  die();
}else if(isset($_SESSION['id_officer'])){
  header("Location: officer/dashboard.php");
  die();
}else if(!empty($_POST)){
  if(isset($_POST['username'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])){
    require_once("main.php");
    $sql = new SQL();
    $bind[':username'] = strtolower($_POST['username']);
    $usercheck = $sql->run("SELECT * FROM user WHERE username = :username", $bind);
    if(empty($usercheck)){
      $_SESSION['error'] = "username";
      header("Location: login.php");
      die();
    }else{
      $bind[':password'] = hash('sha256', $_POST['password']);
      $validate = $sql->run("SELECT * FROM user WHERE username = :username AND password = :password", $bind);
      if(empty($validate)){
        $_SESSION['error'] = "password";
        header("Location: login.php");
        die();
      }else{
        unset($bind);
        $bind[':id'] = $validate[0]['userID'];
        $isApplicant = $sql->run("SELECT * FROM applicant WHERE userID = :id", $bind);
        $isOfficer   = $sql->run("SELECT * FROM housingofficer WHERE userID = :id", $bind);
        $_SESSION['id_user'] = $validate[0]['userID'];
        if(empty($isApplicant)){
          $_SESSION['id_officer'] = $isOfficer[0]['staffID'];
          header("Location: officer/dashboard.php");
          die();
        }else{
          $_SESSION['id_applicant'] = $isApplicant[0]['applicantID'];
          header("Location: dashboard.php");
          die();
        }
      }
    }
  }else{
    $_SESSION['error'] = "empty";
    header("Location: login.php");
    die();
  }
}else{
  $error = '';
  if(isset($_SESSION['error'])){
    if($_SESSION['error'] === "empty"){
      $error = "Please fill required fields.";
    }else if($_SESSION['error'] === "notmatch"){
      $error = "Password and Repassword does not match";
    }else if($_SESSION['error'] === "username"){
      $error = "Username does not exist";
    }else if($_SESSION['error'] === "current"){
      $error = "Wrong current password";
    }else if($_SESSION['error'] === "password"){
      $error = "Wrong password";
    }
    $error = '<span class="d-block alert alert-danger text-center w-100 p-1">'.$error.'</span>';
    unset($_SESSION['error']);
  }
  die('<!DOCTYPE html>
<html lang="en" dir="ltr"><head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <title>Login | Kosan</title>
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
      <script src="js/bootstrap.min.js" type="text/javascript"></script>
    </head>
    <body>
    <div class="container row mx-auto mt-5">
    <div class="container-fluid row mx-auto mr-3 mt-3">
              <div class="col-sm-1"><img src="img/logo_dbkl.png" class="img-fluid" width="150"></div><div class="col-sm-11"><h1>MHS Kosan</h1></div></div>
        <div class="col-md-11 mx-8 mt-3">
        <nav class="breadcrumb">
    <a class="breadcrumb-item" href="index.php">MHSKosan</a>
    <a class="breadcrumb-item" href="#">Register</a>
    </nav></div>
        <div class="col-md-8 border rounded mr-2 d-none d-md-block">
    <img src="img/login_banner3.jpg" class="img-fluid rounded" alt="Login">
        </div>
        <div class="col-md-3 border rounded py-3">
          <form method="POST">
            <div class="form-group">
              <label>Username*</label>
              <input type="text" class="form-control" name="username">
            </div>
            <div class="form-group">
              <label>Password*</label>
              <input type="password" class="form-control" name="password">
            </div>
            <button class="btn btn-primary d-flex ml-auto" type="submit">Sign In</button>
          </form>
    <div class="form-group text-center mt-3"><p>Don&apos;t have account?
      <a href="register.php">Register</a></p></div>
        </div>
      </div>
    
</body></html>');
}
?>