<?php
include('functions.php');

if (isLoggedIn()) {
    if(!isAdmin){
        header('location: index.php');
    }else{
        header('location: admin/home.php');
    }
    
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Registration</title>
    </head>
    <body>
        <div>
            <h2>Login</h2>
        </div>
        <form method="post" action="login.php">

            <div style="color:red"><?php echo display_error(); ?></div>

            <div>
                <label>Username</label>
                <input type="text" name="username" >
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password">
            </div>
            <div>
                <button type="submit" name="login_btn">Login</button>
            </div>
        </form>
    </body>
</html>