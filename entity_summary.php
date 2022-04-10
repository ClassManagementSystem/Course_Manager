<?php
include "includes/head.php";

// Check if person does not have access
if(!isset($_SERVER['HTTP_REFERER'])){
    // Redirect user back to previous page
    header("location: marked_entities.php");
    exit;
}
?>

<style>
.button-link2{
    color: #333366;
    border: none;
    background-color: transparent;
    font-weight: bold;
    font-family: Times New Roman;
    font-size: 17px;
    padding-left: 0;
}
.button-link2:hover{
    background-color: #CCFFCC;
}
</style>

<!-- Displays the coursemanager main content -->
<div class=content>

<button><a href="discussion_board.php">Back</a></button>
<p></p>

<h1><?php echo "Summary: " . $_SESSION['entity_name'];?></h1>
<p></p>
<hr>
<p></p>
<?php
// Display edit entity button if admin or instructor
if ($_SESSION['role_id'] < 3){
    echo "<a href='edit_marked_entity.php'>Edit Marked Entity</a>";
    echo "<p></p>";
    echo "<form method=post action='includes/delete_marked_entity.php'>";
    echo "<button class='button-link2' type='submit' name='delete_ent' value='good' onclick=\"return confirm('Are you sure you want to delete this entity? All files, discussions, and comments related to this entity will be deleted?')\">Delete Marked Entity</button>";
    echo "</form>";
    echo "<p></p>";
    echo "<hr>";
    echo '<p></p>';
}
if (isset($_SESSION['message'])){
    echo "<font color='blue'>".$_SESSION['message']."</font><br><br>";
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])){
    echo "<font color='red'>".$_SESSION['error']."</font><br><br>";
    unset($_SESSION['error']);
}
?>

<!-- Display discussion thread -->
<?php
// Get marked entity data
$data = $link->query("SELECT * FROM marked_entities WHERE marked_entity_id=" . $_SESSION['entity_id']);
if($data -> num_rows>0){
    while($row = mysqli_fetch_array($data,MYSQLI_NUM)){
        $attachment_id = $row[8];
        $section_id = $row[1];
        $name = $row[2];
        $post_date = $row[3];
        $due_date = $row[4];
        $type = $row[5];
        $work_type = $row[6];
        $viewable_to = $row[7];
        if($attachment_id != null && trim($attachment_id) != ''){
            $attachment_data = $link->query("SELECT * FROM attachments WHERE file_id = $attachment_id ");
            $attachment_row = mysqli_fetch_row($attachment_data);
            $file = $attachment_row[1];
            $real_file_name = $attachment_row[2];
        }
        $desc = $row[9];
    }
}

// Converting $viewable_to to more readable text
if($viewable_to == ',all,'){
    $view = "All";
}
else{
    $viewable_to = substr($viewable_to, 1, -1);
    $view_array = explode (",", $viewable_to);
    $view = "";
    foreach($view_array as $value){
        $data = $link->query("SELECT name FROM rtc55314.groups WHERE group_id=$value");
        if($data -> num_rows>0){
            $group_data = $data->fetch_assoc();
            $group_name = $group_data['name'];
            $view = $view . $group_name . ", ";
        }
    }
    $view = substr($view, 0, -2);
}

// Converting type to more readable text
if($type == 'asg'){
    $type = 'Assigment';
}
elseif($type == 'prog'){
    $type = 'Project';
}
else{
    $type = 'Other';
}

echo "Course Info: <font color='darkblue'>" . $_SESSION['code'] . " - " . $_SESSION['term'] . " " . $_SESSION['year'] . " (Section " . $_SESSION['section_name'] . ")</font><br>";
echo "Entity name: <font color='darkblue'>" . $name . "</font><br>";
//echo "Posted By: <font color='darkblue'>" . "</font><br>";
echo "Post date: <font color='darkblue'>" . $post_date . "</font><br>";
echo "Due date: <font color='darkblue'>" . $due_date . "</font><br>";
echo "Type: <font color='darkblue'>" . $type . "</font><br>";
echo "Work Type: <font color='darkblue'>" . ucfirst($work_type) . "</font><br>";
echo "Viewable to (assigned to): <font color='darkblue'>" . $view . "</font><br>";
echo "File attachment: <a href='download.php?name=".$real_file_name."'> $file </a><br>";
echo "Description: <font color='darkblue'>" . $desc . "</font><br>";
echo "<p></p>";
echo "Files uploaded to this entity:";
echo "<p></p>";
echo "<table><tbody><tr><th>File Name</th><th>Author</th><th>Date Uploaded</th><th>Last Modified</th><th>Downloaded by</th><th>Viewable To</th><th>Deleted?</th></tr>";
// Get marked entity data
$data = $link->query("SELECT mf.file_id, mf.file_name, mf.file_location, mf.uploaded_at, u.username, mf.viewable_to, mf.description FROM marked_entity_files mf, users u WHERE mf.marked_entity_id=" . $_SESSION['entity_id'] . " and mf.uploaded_by = u.user_id");
if($data -> num_rows>0){
    echo "<tb>";
    while($row = mysqli_fetch_array($data,MYSQLI_NUM)){

        $file_id = $row[0];
        $file_name = $row[1];
        $file_location = $row[2];
        $uploaded_at = $row[3];
        $uploaded_by = $row[4];
        $viewable_to = $row[5];
        $description = $row[6];
        echo "<tr><td> <a href='download.php?name=". $file_location ."'> " . $file_name . "</a></td><td>" . $uploaded_by . "</td><td>" . $uploaded_at . "</td><td>" . "last_modified" . "</td><td>" . "downloaded_by" . "</td><td>" . $viewable_to . "</td><td>" . "is_deleted" . "</td></tr>";
        
    }
    echo "</tbody></table>";
}
?>