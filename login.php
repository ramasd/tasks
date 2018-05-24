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
        <title>FIFA World Cup 2018 Totalizator</title>
        <style>
            /* body {background-color: powderblue;} */
            body {
                background-image: url("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkmQ3QID628msz_sj4js94mWzg-XNgWmd45kDPHae3UoyzEsi6");
                /* height: 100%;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover; */
            }
            #title, #login, #form {
                text-align: center;
            }

        </style>
    </head>
    <body>
        <div id="title">
            <h1>FIFA World Cup 2018</h1>
            <h1>Totalizator</h1>
        </div>
        <br><br><br><br>
        <div id="login">
            <h2>Login</h2>
        </div>
        <div id="form">
            <form method="post" action="login.php">

                <div style="color:red; background-color:black"><?php echo display_error(); ?></div>
                <div>
                    <input style="background-color:powderblue" type="text" name="username" placeholder="Username" >
                </div>
                <div>
                    <input style="background-color:powderblue" type="password" name="password" placeholder="Password">
                </div>
                <br>
                <div>
                    <button style="background-color:#154545; color:powderblue" type="submit" name="login_btn">Login</button>
                </div>
            </form>
        </div>
    </body>
</html>