
<?php
//put sha1() encrypted password here - example is 'hello'
$username = 'petsplease';
$password = 'ab4d8d2a5f480a137067da17100271cd176607a1';

session_start();
if (!isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = false;
}

if (isset($_POST['password']) && isset($_POST['username'])) {
    if (sha1($_POST['password']) == $password && $_POST['username'] == $username) {
        $_SESSION['loggedIn'] = true;
    } else {
        die ('Incorrect password');
    }
} 

if (!$_SESSION['loggedIn']): ?>

<html><head><title>Login</title></head>
  <body>
    <p>You need to login</p>
    <form method="post">
      Username: <input type="username" name="username"> <br />  
      Password: <input type="password" name="password"> <br />
      <input type="submit" name="submit" value="Login">
    </form>
  </body>
</html>

<?php
exit();
endif;
?>