<!DOCTYPE html >
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>Project_Login</title>
  </head>
  <body>
    <form action="Login_post.php" method="post" >
      UserName: <input type="text" name="username"/>
      Password: <input type="password" name="password" />
      <br>
      <input type="submit" name="login" value="Login"/><br>
      <a href='signup.php' style="text-decoration: none;"> <input type="button" id='signupButton' value="Sign up"/> </a>
      <br>
      <label id="loginstatus" style="color:Red; display:none" >UserName/Password combination is not correct</label>
    </form>


  </body>
</html>
