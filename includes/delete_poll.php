<?php
session_start();
include "./config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $poll_id = $link->real_escape_string(trim($_POST["delete_poll"]));

    // Delete poll in poll_questions sql table
    $sql = "DELETE FROM poll_questions WHERE id=$poll_id";

    // Check whether delete statement work
    try{
        $link->query($sql);
        $_SESSION['message'] = "Poll has been successfully deleted.";
        // Redirect user back to previous page
        header("location: ../discussion_board.php");
    }
    catch(Exception $e){
        $_SESSION['error'] = "Sorry, we have run into a database error. Please try again.<br><br>Error: " . $e;
        // Redirect user back to previous page
        header("location: ../discussion_board.php");
        exit;
    }
    // Log
    $f_sql = addslashes($sql);
    $link->query("SET FOREIGN_KEY_CHECKS=0");
    $link->query("INSERT INTO marked_entities_log (marked_entity_id, user_id, fname, lname, query, log_time) VALUES (" . $_SESSION['entity_id'] . ", " . $_SESSION['id'] . ", '" . $_SESSION['fname'] . "', '" . $_SESSION['lname'] . "', '$f_sql', SYSDATE())");
    $link->query("SET FOREIGN_KEY_CHECKS=1");
}
else{
    // Redirect user to welcome page
    header("location: ../index.php");
    exit;
}

?>