<?php
if (!isset($_SESSION)) {
    session_start();
}
$errors = []; // Changed to a more conventional variable name

// Connect to database
$db = mysqli_connect('localhost', 'root', '', 'bis2024'); // Changed to the correct database name

// If user clicked the register button
if (isset($_POST['sub'])) { // Changed to the correct name attribute of the signup submit button
    $username = mysqli_real_escape_string($db, $_POST['name']); // Changed to the correct form field name
    $email = mysqli_real_escape_string($db, $_POST['eml']); // Changed to the correct form field name
    $password1 = mysqli_real_escape_string($db, $_POST['pass']); // Changed to the correct form field name
    $password2 = mysqli_real_escape_string($db, $_POST['cpass']); // Changed to the correct form field name

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password1)) {
        array_push($errors, "Password is required");
    }
    if (empty($password2)) {
        array_push($errors, "Confirm password is required");
    }
    if ($password1 != $password2) {
        array_push($errors, "Passwords do not match");
    }
    if (count($errors) == 0) {
        // Hash password before storing
        //$passwordHash = password_hash($password1, PASSWORD_DEFAULT);

        // Insert into database
        $sql = "INSERT INTO user1 (name, email, password) VALUES ('$username', '$email', '$password2')";
        mysqli_query($db, $sql);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now registered and logged in";
        header('location: login1.html'); // Redirect to homepage after registration
    }
}

// Login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($db, $_POST['un']);
    $password = mysqli_real_escape_string($db, $_POST['psw1']);

    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    if (count($errors) == 0) {
        $query = "SELECT * FROM user WHERE name = '$username'"; // Fixed table name as per database
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if ($password == $row['password']) {
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "Welcome, you are now logged in";
                header('location: index.html'); // Redirect to homepage after login
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        } else {
            array_push($errors, "User not found");
        }
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('location: login1.html'); // Changed to correct logout redirection
}
?>