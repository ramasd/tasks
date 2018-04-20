<?php
include('../functions.php');
if (!isAdmin()) {
    header('location: ../index.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create User</title>
    </head>
    <body>
        <div>
            <h2>Create user</h2>
        </div>

        <form method="post" action="create_user.php">

            <?php echo display_error(); ?>

            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div>
                <label>User type</label>
                <select name="user_type" required>
                    <option value="" selected disabled></option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password_1" required>
            </div>
            <div>
                <label>Confirm password</label>
                <input type="password" name="password_2" required>
            </div>
            <div>
                <button type="submit" name="register_btn">Create user</button>
                <a href="home.php"><-- Back</a>
            </div>
        </form>
    </body>
</html>