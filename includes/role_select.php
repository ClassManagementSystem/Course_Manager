<?php
// Author: 40215517
// Tester: 40186828

session_start();
include "config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if role_id is empty
    if(empty($link->real_escape_string(trim($_POST["role_id"])))){
        echo "Could not find role_id.";
    } else{
        $_SESSION['role_id'] = $link->real_escape_string(trim($_POST["role_id"]));
		if($_SESSION['role_id'] == 1){
            // Redirect user to admin page
        header("location: ../manage_users.php");
        }
        else{
            // Redirect user to course list
            header("location: ../course_list.php");
        }
    }
}
else{
    header("location: ../index.php");
}
?>