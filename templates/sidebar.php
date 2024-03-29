<html>

<style type="text/css">
a:link {color: #333366}
a:visited {color: #333366}
a:hover {background: #CCFFCC}
a {text-decoration: none}
</style>

<head></head>

<?php
// Display sidebar for Inbox while in mail
if(strpos($_SERVER['REQUEST_URI'],'inbox.php') != false || strpos($_SERVER['REQUEST_URI'],'compose_mail.php') != false || strpos($_SERVER['REQUEST_URI'],'sent_box.php') != false || strpos($_SERVER['REQUEST_URI'],'mail.php') != false){ ?>
	<body bgcolor=#33cccc>
	<b><font size=4>
	<ul>
	<li><a href='compose_mail.php'><b><font color=black>Compose Mail</b></a></li>
	<p></p>
	<p></p>
	<li><a href='inbox.php'><b><font color=black>Inbox</b></a></li>
	<li><a href='sent_box.php'><b><font color=black>Sent</b></a></li>
	</b>
<?php 
}
// Display sidebar for Admin while in admin pages
elseif((isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],'role_list.php') != false) || ($_SESSION['role_id'] == 1 && isset($_SESSION['admin_pages']))){
	$_SESSION['admin_pages'] = true;
	?>
	<body bgcolor=#33cccc>
	<b><font size=4>
	<ul>
	<b>You are an admin.</b><br><br>
	<li><a href='manage_users.php'><b><font color=black>Manage Users</b></a></li>
	<li><a href='manage_courses.php'><b><font color=black>Manage Courses</b></a></li>
	</b>
<?php 
}
else{ ?>

<body bgcolor=#33cccc>
<b><font size=4><i><?php echo $_SESSION['code'] . " / " . $_SESSION['term'] . " " . $_SESSION['year'] . "<br>Section " . $_SESSION['section_name'];?></i></font></b><hr>
<b><font size=4>
<ul>

<!--menu for Sidebar -->	
<?php
	// Display sidebar for Admin while in a course
	if($_SESSION['role_id'] == 1){
		echo "You are an admin.<br><br>";
		echo "<li><a href='manage_section_users.php?id=".$_SESSION['section_id']."'><b><font color=black>Manage Students</b></a></li>";
		echo "<li><a href='manage_section_tas.php?id=".$_SESSION['section_id']."'><b><font color=black>Manage Teaching Assistants</b></a></li>";
		echo "<li><a href='manage_groups.php?id=". $_SESSION['section_id'] ."'><b><font color=black>Manage Groups</b></a></li>";
		echo "<br>";
		echo "<li><a href='post_notices.php'><b><font color=black>Post Notices</b></a></li>";
		echo "<li><a href='marked_entities.php'><b><font color=black>Marked Entities</b></a></li>";
		echo "<br>";
		echo "<li><a href=change_password.php><b><font color=black>Change Password</b></a></li>";
	}
	// Display sidebar for Instructor
	elseif($_SESSION['role_id'] == 2){
		echo "You are an instructor.<br><br>";
		echo "<li><a href='manage_section_users.php?id=".$_SESSION['section_id']."'><b><font color=black>Manage Students</b></a></li>";
		echo "<li><a href='manage_section_tas.php?id=".$_SESSION['section_id']."'><b><font color=black>Manage Teaching Assistants</b></a></li>";
		echo "<li><a href='manage_groups.php?id=". $_SESSION['section_id'] ."'><b><font color=black>Manage Groups</b></a></li>";
		echo "<br>";
		echo "<li><a href='post_notices.php'><b><font color=black>Post Notices</b></a></li>";
		echo "<li><a href='marked_entities.php'><b><font color=black>Marked Entities</b></a></li>";
		echo "<br>";
		echo "<li><a href=change_password.php><b><font color=black>Change Password</b></a></li>";
	}
	// Display sidebar for Teaching Assistant
	elseif($_SESSION['role_id'] == 3){
		echo "You are a TA.<br><br>";
		echo "<li><a href='marked_entities.php'><b><font color=black>Marked Entities</b></a></li>";
		echo "<br>";
		echo "<li><a href=change_password.php><b><font color=black>Change Password</b></a></li>";
	}
	// Display sidebar for Student
	elseif($_SESSION['role_id'] == 4){
		echo "You are a student.<br><br>";
		echo "<li><a href='student_group.php'><b><font color=black>View Group Information</b></a></li>";
		echo "<li><a href='marked_entities.php'><b><font color=black>Marked Entities</b></a></li>";
		echo "<li><a href='meetings.php'><b><font color=black>Meetings</b></a></li>";
		echo "<br>";
		echo "<li><a href=change_password.php><b><font color=black>Change Password</b></a></li>";
	}
}
?>

</ul>
</body>
</html>