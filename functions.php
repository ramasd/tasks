<?php

session_start();

$db = mysqli_connect('localhost', 'root', '', 'fifa');

$username = "";
$email = "";
$errors = array();

$title = "";
$description = "";
$id = 0;
$completed = 0;
$user = "";
$update = false;

if (isset($_POST['register_btn'])) {
    register();
}

function register() {

    global $db, $errors, $username, $email;

    $username = escape($_POST['username']);
    $email = escape($_POST['email']);
    $password_1 = escape($_POST['password_1']);
    $password_2 = escape($_POST['password_2']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    $query = "SELECT COUNT(*) FROM users WHERE username = '$_POST[username]'";
    $usr = mysqli_query($db, $query);
    if(!$usr)
        exit("Klaida - " . mysqli_error($db));
    mysqli_data_seek($usr,0);
    $total = mysqli_fetch_row($usr);
    if($total[0] > 0){
        array_push($errors, "Username '$username' already exists, please choose another one.");
    }
    
    if (count($errors) == 0) {
        $password = md5($password_1);

        if (isset($_POST['user_type'])) {
            $user_type = escape($_POST['user_type']);
            $query = "INSERT INTO users (username, email, user_type, password)"
                    . "VALUES('$username', '$email', '$user_type', '$password')";
            mysqli_query($db, $query);
            $_SESSION['success'] = "New user successfully created!!";
            header('location: home.php');
        } else {
            $query = "INSERT INTO users (username, email, user_type, password)"
                    . "VALUES('$username', '$email', 'user', '$password')";
            mysqli_query($db, $query);

            $logged_in_user_id = mysqli_insert_id($db);

            $_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        }
    }
}

function getUserById($id) {
    global $db;
    $query = "SELECT * FROM users WHERE id=" . $id;
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}

function escape($val) {
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
    global $errors;

    if (count($errors) > 0) {
        echo '<div>';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}

function isLoggedIn() {
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user = $_POST['user'];

    mysqli_query($db, "INSERT INTO predictions (title, description, user)"
            . "VALUES ('$title', '$description', '$user')");
    $_SESSION['message'] = "Task added!";
    header('location: admin/home.php');
}

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user = $_POST['user'];
    $completed = $_POST['completed'];

    $update = "UPDATE predictions SET title='$title',description='$description',"
            . "user='$user', completed='$completed' WHERE id=$id";
    mysqli_query($db, $update);

    $_SESSION['message'] = "Task updated!";
    if ($_SESSION['user']['user_type'] == 'admin') {
        header('location: admin/home.php');
    } else {
        header('location: index.php');
    }
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    mysqli_query($db, "DELETE FROM predictions WHERE id=$id");
    $_SESSION['message'] = "Task deleted!";
    header('location: admin/home.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}

if (isset($_POST['login_btn'])) {
    login();
}

function login() {
    global $db, $username, $errors;

    $username = escape($_POST['username']);
    $password = escape($_POST['password']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);

        $query = "SELECT * FROM users WHERE username='$username'"
                . "AND password='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $logged_in_user = mysqli_fetch_assoc($results);
            if ($logged_in_user['user_type'] == 'admin') {
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success'] = "You are now logged in";
                header('location: admin/home.php');
            } else {
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success'] = "You are now logged in";
                header('location: index.php');
            }
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

function isAdmin() {
    if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

function issetEdit() {
    global $db;
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $update = true;
        $record = mysqli_query($db, "SELECT * FROM predictions WHERE id=$id");

        if (!empty($record)) {
            $n = mysqli_fetch_array($record);
            $title = $n['title'];
            $description = $n['description'];
            $user = $n['user'];
            $completed = $n['completed'];
        }
        return array($id, $title, $description, $user, $completed, $update);
    }
}

function message() {
    if (isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

//// žiūrėt:
function insert() {
    global $db, $errors, $username, $email;
    if(mysqli_connect_errno()){
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }else{
        var_dump($_POST);
        $sql = "INSERT INTO predictions (user_id, match_id, home_prediction, away_prediction) VALUES (". $_SESSION['user']['id'].", ".$_POST['match_id'].", ".$_POST['home_prediction'].", ".$_POST['away_prediction'].")";
        echo '<br>';
        var_dump($sql);
        echo '<br>';
        echo '<br>';
        echo $_SESSION['user']['id'];
        $res = mysqli_query($db, $sql);
        if($res === TRUE){
            echo "A record has been inserted.";
        }else{
            printf("Could not insert record: %s\n". mysqli_error($db));
        }
        mysqli_close($db);
        echo "<meta http-equiv='refresh' content='0'>";
    }
}