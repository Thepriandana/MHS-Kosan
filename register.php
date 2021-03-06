<?php
session_start();
if(isset($_SESSION['id_applicant'])){
  header("Location: dashboard.php");
}else if(isset($_SESSION['id_officer'])){
  header("Location: officer/dashboard.php");
  die();
}else if(!empty($_POST)){
  if(isset($_POST['username'], $_POST['password'],$_POST['repassword'], $_POST['email'], $_POST['name'],  $_POST['monthly'])
  && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['repassword'])
  && !empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['monthly'])){
    if($_POST['password'] !== $_POST['repassword']){
      $_SESSION['error'] = "notmatch";
    }else{
      require_once("main.php");
      $sql = new SQL();
      $bind[':username'] = strtolower($_POST['username']);
      $usercheck = $sql->run("SELECT * FROM user WHERE username = :username", $bind);
      if(!empty($usercheck)){
        $_SESSION['error'] = "username";
        header("Location: register.php");
      }else{
        $bind[':password'] = hash('sha256', $_POST['password']);
        $bind[':name']     = $_POST['name'];
        $userID = $sql->run("INSERT INTO user VALUES('', :username, :password, :name)", $bind);
        unset($bind);
        $bind[':id']       = $userID;
        $bind[':email']    = strtolower($_POST['email']);
        $bind[':monthly']  = $_POST['monthly'];
        $applicantID = $sql->run("INSERT INTO applicant VALUES('', :id, :email, :monthly)", $bind);
        $_SESSION['id_user'] = $userID;
        $_SESSION['id_applicant'] = $applicantID;
        header("Location: dashboard.php");
        die();
      }
    }
  }else{
    $_SESSION['error'] = "empty";
    header("Location: register.php");
  }
}else{
  $error = '';
  if(isset($_SESSION['error'])){
    if($_SESSION['error'] === "empty"){
      $error = "Please fill form correctly";
    }else if($_SESSION['error'] === "notmatch"){
      $error = "Password and Repassword is not match";
    }else if($_SESSION['error'] === "username"){
      $error = "Username is not available";
    }
    $error = '<span class="d-block alert alert-danger text-center w-100 p-1">'.$error.'</span>';
    unset($_SESSION['error']);
  }
  die('<!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <title>Login | Kosan</title>
      <link rel="icon" href="images/logo_kosan.ico">
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
      <script src="js/bootstrap.min.js" type="text/javascript"></script>
    </head>
    <body class="bg-light">
      <div class="container row mx-auto mt-4">
        <div class="col-md-4 border rounded py-3 mx-auto bg-white shadow-sm">
          <form method="POST">
            '.$error.'
            <div class="form-group">
              <label>Username</label>
              <input type="text" placeholder="Eg. jdoe123" class="form-control form-control-sm" name="username" required>
            </div>
            <div class="form-group">
              <label>Password</label>
              <input type="password" placeholder="" class="form-control form-control-sm" name="password" required>
            </div>
            <div class="form-group">
              <label>Re-Password</label>
              <input type="password" class="form-control form-control-sm" name="repassword" required>
            </div>
            <div class="form-group">
              <label>Full name</label>
              <input type="name" placeholder="Eg. John Doe" class="form-control form-control-sm" name="name" required>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" placeholder="Eg. john_doe@gmail.com" class="form-control form-control-sm" name="email" required>
            </div>
            <div class="form-group">
              <label>Monthly Income (RM)</label>
              <input type="number" min="10" max="300" placeholder="Eg. 40.34, 250" class="form-control form-control-sm" name="monthly" required>
            </div>
            <button class="btn btn-primary d-flex mx-auto" type="submit">Register</button>
            <small class=" d-block text-center"><a href="login.php" class="nav-link">Already have an account?</a></small>
          </form>
        </div>
      </div>
    </body>
  </html>');
}
?>
