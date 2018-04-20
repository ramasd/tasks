<?php
include('functions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isAdmin()) {
    header('location: admin/home.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
    </head>
    <body>
        <div>
            <h2>Home Page</h2>
        </div>
        
        <div style="color:blue"><strong><?php message(); ?></strong></div>
 
        <div>
            <div>
                <div>
                    <?php if (isset($_SESSION['user'])) : ?>
                        <strong><?php echo $_SESSION['user']['username']; ?></strong>
                        <i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
                        <br>
                        <a href="index.php?logout='1'" style="color: red;">logout</a>
                    <?php endif ?>
                </div>
                <hr><br>
                <?php
                    $sql = "SELECT * FROM tasks WHERE user='{$_SESSION['user']['id']}'";
                    $results = mysqli_query($db, $sql);
                ?>
                <h1>Tasks</h1>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Completed</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>

                    <?php while ($row = mysqli_fetch_array($results)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <?php if ($row['completed'] == 1) { ?>
                            <td>Yes</td>
                        <?php } else { ?>
                            <td>No</td>
                        <?php } ?>
                        <td>
                            <a href="edit.php?edit=<?php echo $row['id']; ?>">Edit</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>         
            </div>
        </div>
    </body>
</html>