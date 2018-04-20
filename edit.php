<?php
include 'functions.php';
if (!isLoggedIn()) {
    header('location: login.php');
}
if (isAdmin()) {
    header('location: admin/home.php');
}

list($id, $title, $description, $user, $completed) = issetEdit();
?>

<form method="post" action="functions.php" >
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Completed</th>
            </tr>
        </thead>
        <tr>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="title" value="<?php echo $title; ?>">
        <input type="hidden" name="description" value="<?php echo $description; ?>">
        <input type="hidden" name="user" value="<?php echo $user; ?>">
        <td><?php echo $title ?></td>
        <td><?php echo $description ?></td>
        <td>            
            <select name="completed">
                <option value=0>No</option>
                <option value=1 <?php
                if ($completed == 1): echo 'selected'; endif ?>>Yes</option>
            </select>
        </td>
        </tr>
    </table>
    <button type="submit" name="update">Update</button>
    <a href="index.php"><-- Back</a>
</form>