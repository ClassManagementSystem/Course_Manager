<?php
// Author: 40215517
// Edited: 40196855
// Tester: 40186828

session_start();
include "./includes/config.php";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  if(isset($_SESSION['role_id']) && isset($_SESSION['section_id'])){
    header("location: index.php");
    exit;
  }
}
?>

<style>
div.error{
    text-align:center;
}
</style>

<html>
<head>
<Title>CGA - The CrsMgr Group-work Assistant!!!</title>
</head>

<body bgcolor=#faf0e6>
<br><br><br>
<table border=0 width=100%>
<tr><td align=center><img src="pics/crsmgr.jpg" border=0></td></tr>
<tr><td><br></td></tr>
<tr bgcolor=#3399ff>
<td align=center><b><font size=5>
Welcome to <font color=Red>C</font><font color=yellow>r</font><font color=#00ff00>s</font><font color=#663300>M</font><font color=blue>g</font><font color=#ff3399>r</font> -- The CrsMgr Group-work Assistant!</font></b></td>
</tr>
</table>
<br><br>

<div class='error'>
<?php
// Print error message from db and unset error
if (isset($_SESSION['error'])){
  echo "<font color='red'>".$_SESSION['error']."</font>";
  unset($_SESSION['error']);
}
if (isset($_SESSION['message'])){
    echo "<font color='blue'>".$_SESSION['message']."</font>";
    unset($_SESSION['message']);
  }
?>
</div>

<form name=login method=post action="includes/login.php">
<table border=0 align=center>

<tr>
    <td><b>User Name:</b></td><td><input type=text name=username maxlength=20 size=20 required></td>
</tr>

<tr>
    <td><b>Password:</b></td><td><input type=password name=password maxlength=20 size=21 required></td>
</tr>

<tr>
   <td colspan=2 align=center>
      <input type=Submit value="Login">
      <input type=Reset value ="Clear">
   </td>
</tr>

<tr>
    <td  colspan=2><br></td>
</tr>

<tr>
   <td align=center colspan=2><a href=change_password.php>Change Password</a></td>
</tr>

</table>
<br>
</form>

</body>
</html>

<!-- set focus on  username -->
<script language = "javascript">
  document.login.username.focus()
</script>

