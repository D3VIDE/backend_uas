<?php
session_start();
include('DatabaseConnection.php');  // Make sure your database connection is included here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to fetch username and password (assuming password is stored as plain text in the database)
    $query = "SELECT username, password, nama_admin,status_aktif FROM user_admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if username exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();  // Fetch the user data
        $stored_password = $row['password'];  // The plain text password from the database
        $stored_admin = $row['nama_admin'];
        // If password is correct, set session and redirect
        if ($password == $stored_password) {
          if($row['status_aktif'] == 1){
            $_SESSION["nama_admin"] = $stored_admin;
            header("Location: admin_page.php");
            exit();
          }else
            echo"<script>alert('Your account is inactive. Please contact support.');</script>";
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <style>
    /* Centering the login form on the screen */
    body, html {
      height: 100%;
      margin: 0;
    }

    .container {
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      width: 100%;
      max-width: 400px;
      padding: 30px;
      border: 2px solid #ddd;
      border-radius: 10px;
      background-color: #f8f9fa;
    }

    .login-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 10px;
    }

    .btn-primary {
      width: 100%;
      border-radius: 10px;
    }

    .footer-text {
      text-align: center;
      margin-top: 20px;
    }
  </style>
  <body>
    <div class="container">
      <div class="login-box">
        <div class="login-header">
          <h3>Login</h3>
        </div>
        <form action="login.php" method="POST">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
