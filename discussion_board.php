<?php
// Author: 40215517
// Edited: 40196855
// Tester: 40186828

include "includes/head.php";

// Check if person does not have access
if (!isset($_SERVER['HTTP_REFERER'])) {
    // Redirect user back to previous page
    header("location: marked_entities.php");
    exit;
}

// Check whether due date is passed
$data = $link->query("SELECT due_date, DATE(SYSDATE()) FROM marked_entities WHERE marked_entity_id=" . $_SESSION['entity_id']);
if ($data->num_rows > 0) {
    $entity_data = $data->fetch_assoc();
    $due_date = $entity_data['due_date'];
    $current_date = $entity_data['DATE(SYSDATE())'];
}
$readonly = false;
if ($due_date < $current_date) {
    $readonly = true;
}
?>

<!-- Displays the coursemanager main content -->
<div class=content>

    <button><a href="marked_entities.php">Back</a></button>
    <p></p>
    <?php if ($readonly) { ?>
        <font color='red'>This marked entity is in read-only mode (due date is past).</font>
        <p></p>
    <?php } ?>

    <h1><?php echo $_SESSION['entity_name']; ?></h1>
    <p></p>
    <hr>
    <p></p>
    <a href="entity_summary.php">Summary</a>
    <?php
    if ($_SESSION['role_id'] < 3) {
        echo "| <a href='entity_log.php'>Audit Log</a>";
    }
    echo "<p></p>";
    echo "<p></p>";
    if (!$readonly) {
        if ($_SESSION['role_id'] < 3) {
            // Display create a custom category if admin or instructor
            echo "<a href='add_category.php'>Create a Custom Category</a>";
            echo " | ";
        }
    ?>
        <a href="add_topic.php">Create a Topic</a>
        |
        <a href="add_poll.php">Create a Poll</a>
        |
        <a href="add_file_to_entity.php">Upload a File</a>
    <?php } ?>
    <p></p>
    <hr>
    <p></p>

    <?php
    // Display success message when adding marked entity
    if (isset($_SESSION['message'])) {
        echo "<font color='blue'>" . $_SESSION['message'] . "</font><br><br>";
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo "<font color='red'>" . $_SESSION['error'] . "</font><br><br>";
        unset($_SESSION['error']);
    }
    ?>

    <!-- Display discussions available -->
    <?php
    // Display the posts viewable to admin, instructor, and TA
    if ($_SESSION['role_id'] < 4) {
        $data = $link->query("SELECT * FROM forum_categories WHERE marked_entity_id=" . $_SESSION['entity_id']);
        if ($data->num_rows > 0) {
            while ($row = mysqli_fetch_array($data, MYSQLI_NUM)) {
                $cat_id = $row[0];
                $cat_name = $row[2];


                $data2 = $link->query("SELECT ft.topic_id, ft.title, ft.date created, CONCAT(u.fname,' ',u.lname) author, max(fr.date) last_modified, count(fr.reply_id) num_replies FROM forum_topics ft JOIN forum_replies fr ON ft.topic_id=fr.topic_id JOIN users u ON ft.topic_by=u.user_id WHERE category_id=$cat_id GROUP BY ft.topic_id, ft.title, ft.date, CONCAT(u.fname,' ',u.lname) ORDER BY ft.date;");
                $data3 = $link->query("SELECT pq.id, pq.question, pq.created_on, CONCAT(u.fname,' ',u.lname) author, pq.end_date, count(pr.id) num_votes FROM poll_questions pq LEFT JOIN poll_responses pr ON pq.id = pr.question_id JOIN users u on pq.user_id = u.user_id where category_id = $cat_id GROUP BY pq.id, pq.question, pq.created_on, CONCAT(u.fname,' ',u.lname) ORDER BY pq.created_on;");
                if ($data3->num_rows > 0) {
                    echo $cat_name . ": Polls";
                    echo "<table><tbody><tr><th>Poll Title</th><th>Date Created</th><th>End date</th><th>Poll Created by</th><th>Votes</th><th>Actions</th></tr>";
                    while ($row3 = mysqli_fetch_array($data3, MYSQLI_NUM)) {
                        $poll_id = $row3[0];
                        $poll_title = $row3[1];
                        $poll_date = $row3[2];
                        $poll_author = $row3[3];
                        $poll_modified = $row3[4];
                        $poll_replies = $row3[5];
                        echo "<tr><td><form class='form-button' method=post action='view_poll.php'><button class='button-link' name='poll_id' value=$poll_id type='submit'>" . $poll_title . "</button></form></td>";
                        echo "<td>" . $poll_date . "</td>";
                        echo "<td>" . $poll_modified . "</td>";
                        echo "<td>" . $poll_author . "</td>";
                        echo "<td>" . $poll_replies . "</td>";
                        echo "<td><form class='form-button' method=post action='includes/delete_poll.php'>";
                        echo "<button type='submit' name='delete_poll' value=$poll_id onclick=\"return confirm('Are you sure you want to delete this poll?')\">Delete poll</button>";
                        echo "</form></td></tr>";
                    }
                    echo "</tbody></table>";
                    // Delete category for admin or instructor
                    if ($_SESSION['role_id'] < 3 && !str_contains($cat_name, 'Public')) {
                        echo "<form method=post action='includes/delete_category.php'>";
                        echo "<button type='submit' name='delete_cat' value=$cat_id onclick=\"return confirm('Are you sure you want to delete this category? It will delete the discussions AND polls under this category.')\">Delete Category</button>";
                        echo "</form>";
                    }
                }

                if ($data2->num_rows > 0) {

                    echo $cat_name. ": Discussions";
                    echo "<table><tbody><tr><th>Topic Title</th><th>Date Created</th><th>Latest Post</th><th>Author</th><th>Replies</th><th>Actions</th></tr>";
                    while ($row2 = mysqli_fetch_array($data2, MYSQLI_NUM)) {
                        $topic_id = $row2[0];
                        $topic_name = $row2[1];
                        $topic_date = $row2[2];
                        $topic_author = $row2[3];
                        $topic_modified = $row2[4];
                        $topic_replies = $row2[5];
                        echo "<tr><td><form class='form-button' method=post action='includes/topic_select.php'><button class='button-link' name='topic_id' value=$topic_id type='submit'>" . $topic_name . "</button></form></td>";
                        echo "<td>" . $topic_date . "</td>";
                        echo "<td>" . $topic_modified . "</td>";
                        echo "<td>" . $topic_author . "</td>";
                        echo "<td>" . $topic_replies . "</td>";
                        echo "<td><form class='form-button' method=post action='includes/delete_topic.php'>";
                        echo "<button type='submit' name='delete_topic' value=$topic_id onclick=\"return confirm('Are you sure you want to delete this topic?')\">Delete Topic</button>";
                        echo "</form></td></tr>";
                    }
                    echo "</tbody></table>";
                    // Delete category for admin or instructor
                    if ($_SESSION['role_id'] < 3 && !str_contains($cat_name, 'Public')) {
                        echo "<form method=post action='includes/delete_category.php'>";
                        echo "<button type='submit' name='delete_cat' value=$cat_id onclick=\"return confirm('Are you sure you want to delete this category? It will delete the discussions AND polls under this category.')\">Delete Category</button>";
                        echo "</form>";
                    }
                    echo "<br>";
                }
            }
        }
    }
    // Display posts viewable to students
    else {
        // Get the groups that the student belong to
        $groups = ['all'];
        $data = $link->query("SELECT group_id FROM group_users WHERE user_id=" . $_SESSION['id']);
        if ($data->num_rows > 0) {
            while ($row = mysqli_fetch_array($data, MYSQLI_NUM)) {
                array_push($groups, (string)$row[0]);
            }
        }

        // Display the discussion boards available
        foreach ($groups as $value) {
            $data = $link->query("SELECT * FROM forum_categories WHERE (viewable_to LIKE '%," . $value . ",%') AND marked_entity_id=" . $_SESSION['entity_id']);
            if ($data->num_rows > 0) {
                while ($row = mysqli_fetch_array($data, MYSQLI_NUM)) {
                    $cat_id = $row[0];
                    $cat_name = $row[2];


                    // Print the topics that are still viewable to user - encase they leave a group, topics should not be viewable
                    if ($value == 'all') {
                        $data2 = $link->query("SELECT ft.topic_id, ft.title, ft.date created, CONCAT(u.fname,' ',u.lname) author, max(fr.date) last_modified, count(fr.reply_id) num_replies 
                    FROM forum_topics ft 
                    JOIN forum_replies fr ON ft.topic_id=fr.topic_id 
                    JOIN users u ON ft.topic_by=u.user_id WHERE category_id=$cat_id 
                    GROUP BY ft.topic_id, ft.title, ft.date, CONCAT(u.fname,' ',u.lname) ORDER BY ft.date;");

                        $poll_data2 = $link->query("SELECT pq.id, pq.question, pq.created_on, CONCAT(u.fname,' ',u.lname) author, pq.end_date, count(pr.id) num_votes FROM poll_questions pq LEFT JOIN poll_responses pr ON pq.id = pr.question_id JOIN users u on pq.user_id = u.user_id where category_id = $cat_id GROUP BY pq.id, pq.question, pq.created_on, CONCAT(u.fname,' ',u.lname) ORDER BY pq.created_on;");
                    } else {
                        $data2 = $link->query("SELECT ft.topic_id, ft.title, ft.date created, CONCAT(u.fname,' ',u.lname) author, max(fr.date) last_modified, count(fr.reply_id) num_replies 
                    FROM forum_topics ft 
                    JOIN forum_replies fr ON ft.topic_id=fr.topic_id 
                    JOIN users u ON ft.topic_by=u.user_id WHERE category_id=$cat_id 
                    AND ft.date<=(select coalesce(left_group_date,date('9999-01-01')) FROM group_users WHERE group_id=$value AND user_id=" . $_SESSION['id'] . ") 
                    GROUP BY ft.topic_id, ft.title, ft.date, CONCAT(u.fname,' ',u.lname) ORDER BY ft.date;");

                        $poll_data2 = $link->query("SELECT pq.id, pq.question, pq.created_on, CONCAT(u.fname,' ',u.lname) author, pq.end_date, count(pr.id) num_votes FROM poll_questions pq LEFT JOIN poll_responses pr ON pq.id = pr.question_id JOIN users u on pq.user_id = u.user_id where category_id = $cat_id and pq.end_date<=(select coalesce(left_group_date,date('9999-01-01')) FROM group_users WHERE group_id=$value AND user_id=" . $_SESSION['id'] . ")  GROUP BY pq.id, pq.question, pq.created_on, CONCAT(u.fname,' ',u.lname) ORDER BY pq.created_on;");
                    }

                    if ($poll_data2->num_rows > 0) {
                        echo $cat_name . ": Polls";
                        echo "<table><tbody><tr><th>Poll Title</th><th>Date Created</th><th>End date</th><th>Poll Created by</th><th>Votes</th></tr>";
                        while ($row3 = mysqli_fetch_array($poll_data2, MYSQLI_NUM)) {
                            $poll_id = $row3[0];
                            $poll_title = $row3[1];
                            $poll_date = $row3[2];
                            $poll_author = $row3[3];
                            $poll_modified = $row3[4];
                            $poll_replies = $row3[5];
                            echo "<tr><td><form class='form-button' method=post action='view_poll.php'><button class='button-link' name='poll_id' value=$poll_id type='submit'>" . $poll_title . "</button></form></td>";
                            echo "<td>" . $poll_date . "</td>";
                            echo "<td>" . $poll_modified . "</td>";
                            echo "<td>" . $poll_author . "</td>";
                            echo "<td>" . $poll_replies . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table><br>";
                    }


                    if ($data2->num_rows > 0) {
                        echo $cat_name . ": Discussions";
                        echo "<table><tbody><tr><th>Topic Title</th><th>Date Created</th><th>Latest Post</th><th>Author</th><th>Replies</th></tr>";
                        echo "<form method=post action='includes/topic_select.php'>";
                        while ($row2 = mysqli_fetch_array($data2, MYSQLI_NUM)) {
                            $topic_id = $row2[0];
                            $topic_name = $row2[1];
                            $topic_date = $row2[2];
                            $topic_author = $row2[3];
                            $topic_modified = $row2[4];
                            $topic_replies = $row2[5];
                            echo "<tr><td><button class='button-link' name='topic_id' value='$value,$topic_id' type='submit'>" . $topic_name . "</button></td>";
                            echo "<td>" . $topic_date . "</td>";
                            echo "<td>" . $topic_modified . "</td>";
                            echo "<td>" . $topic_author . "</td>";
                            echo "<td>" . $topic_replies . "</td></tr>";
                        }
                        echo "</form></tbody></table>";
                        echo "<br>";
                    }

                }
            }
        }
    }
    ?>

</div>

</body>

</html>