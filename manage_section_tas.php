<?php
//40197292
include "includes/head.php";

if(!isset($_GET['id'])){
    // redirect to show page
    die('id not provided');
}
$id = (int)$_GET['id'];

$result = mysqli_query($link,"SELECT user_id,username from users where user_id IN(SELECT user_id FROM users_roles_sections WHERE section_id = '$id' and role_id = 3)");
$sql = mysqli_query($link,"SELECT section_name FROM sections WHERE section_id = '$id'");
$row2 = mysqli_fetch_array($sql);
echo "<div class='content'>";

// Display success/error message
if (isset($_SESSION['message'])){
  echo "<font color='blue'>".$_SESSION['message']."</font>";
  unset($_SESSION['message']);
}
if (isset($_SESSION['error'])){
    echo "<font color='red'>".$_SESSION['error']."</font>";
    unset($_SESSION['error']);
}

echo "</br>
<h1>Section Name: $row2[0]</h1>
<h2>Current TAs:</h2>
<div  class='form-group'>
<a href='create_section_ta.php?id=".$id."'>
<button style='background-color:pink'>Add TA</button>
</a> 
</div>
</br>";
if(mysqli_num_rows($result)==0)
{
echo "No TA available under this section";
}
else
{
echo "<table border='1'>
<tr>
 <th>TA</th>
 <th>Options</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['username'] . "</td>";
echo "<td><a href='delete_section_ta.php?section_id=".$id."&ta_id=".$row['user_id']."'>Delete</a></td>";
echo "</tr>";
}
echo "</table>";
echo "</div>";
}

mysqli_close($link);
?>