<?php
include('../functions.php');

if (!isAdmin()) {
    $_SESSION['message'] = "You must log in first";
    header('location: ../index.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: ../login.php");
}

list($id, $title, $description, $user, $completed, $update) = issetEdit();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
    </head>
    <body>
        <div>
            <h2>Admin - Home Page</h2>
        </div>
        <div>
            <?php if (isset($_SESSION['success'])) : ?>
                <div style="color:blue">
                    <h3>
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>

            <div>
                <div>
                    <?php if (isset($_SESSION['user'])) : ?>
                        <strong>
                            <?php echo $_SESSION['user']['username']; ?>
                            <i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
                        </strong>
                        <br><a href="create_user.php" style="background-color:lightgreen"> + add user</a>
                        <br><a href="home.php?logout='1'" style="background-color:orange">logout</a>
                    <?php endif ?>
                </div>
            </div>
            <hr>
            <div>
                <div style="color:blue"><strong><?php message(); ?></strong></div>
                <div>
                    <?php 
                        $sql = "SELECT tasks.*, username FROM tasks LEFT JOIN users ON tasks.user = users.id";
                        $results = mysqli_query($db, $sql);
                    ?>

                    <form method="post" action="../functions.php" >
                        <div>
                            <?php if ($update == true): ?>
                                <h2>Edit Task</h2>
                            <?php else: ?>
                                <h2>Add Task</h2>
                            <?php endif ?>

                            <input type="hidden" name="id" value="<?php echo $id; ?>">          
                            <input type="hidden" name="completed" value="<?php echo $completed; ?>">

                            <label>Title</label>
                            <input type="text" name="title" value="<?php echo $title; ?>" required>
                        </div>
                        <div>
                            <label>Description</label>
                            <input type="text" name="description" value="<?php echo $description; ?>" required>
                        </div>
                        <div>
                            <label>Employee</label>
                            <select name="user" required>
                                <option selected disabled></option>
                                <?php
                                $users = mysqli_query($db, "SELECT * FROM users WHERE user_type='user'");
                                while ($row = mysqli_fetch_array($users)) {
                                    if ($user == $row['id']) {
                                        ?>
                                        <option value="<?php echo $row['id'] ?>" selected><?php echo $row['username'] ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $row['id'] ?>"><?php echo $row['username'] ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <?php if ($update == true): ?>
                                <button type="submit" name="update">Update</button>
                                <a href="home.php"><-- Back</a>
                            <?php else: ?>
                                <button type="submit" name="add" >Add</button>
                            <?php endif ?>
                        </div>
                    </form>
                </div>
                <hr>
                <h1>Tasks</h1>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Employee</th>
                            <th>Completed</th>
                            <th colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <?php while ($row = mysqli_fetch_array($results)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <?php if ($row['completed'] == 1) { ?>
                                <td>Yes</td>
                            <?php } else { ?>
                                <td>No</td>
                            <?php } ?>
                            <td>
                                <a href="home.php?edit=<?php echo $row['id']; ?>">Edit</a>
                            </td>
                            <td>
                                <a href="../functions.php?del=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </body>
</html>