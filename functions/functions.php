<?php 


function set_message($message) {

    if (!empty($message)) {
        $_SESSION['message'] = $message;
    } else {
        $_SESSION['message'] = '';
    }
    
}

function display_message() {
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    } else {
        $_SESSION['message'] = "";
    }
}

function token_generator() {
    $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    return $token;
}


function check_email($email) {
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = query($sql);
    confirm($result);
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        return false;
    } else {
        return true;
    }
}

function check_username($username) {
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = query($sql);
    confirm($result);
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        return false;
    } else {
        return true;
    }
}


function register_user($first_name, $last_name, $username, $email, $password) {
    $first_name = escape($first_name);
    $last_name = escape($last_name);
    $username = escape($username);
    $email = escape($email);
    $password = escape($password);

    $password = md5($password);
    $validation = md5($username);

    $sql = "INSERT INTO users(first_name, last_name, username, email, password, validation, active) VALUES('$first_name', '$last_name', '$username', '$email', '$password', '$validation', 0)";

    $results = query($sql);
    confirm($results);
}



function validate_register() {
    if (isset($_POST['register-submit'])) {
        $first_name = htmlentities($_POST['first_name']);
        $last_name = htmlentities($_POST['last_name']);
        $username = htmlentities($_POST['username']);
        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);
        $confirm_password = htmlentities($_POST['confirm_password']);
        
        $errors = [];

        if (strlen($first_name) < 3) {
            $errors[] = 'Your first name must be more than 2 characters';
        }
        if (strlen($first_name) > 29) {
            $errors[] = 'Your first name must be less than 30 characters';
        }



        if (strlen($last_name) < 3) {
            $errors[] = 'Your last name must be more than 2 characters';
        }
        if (strlen($last_name) > 29) {
            $errors[] = 'Your last name must be less than 30 characters';
        }



        if (strlen($username) < 5) {
            $errors[] = 'Your username must be more than 5 characters';
        }
        if (strlen($username) > 29) {
            $errors[] = 'Your username must be less than 30 characters';
        }



        if (strlen($password) < 5) {
            $errors[] = 'Your password must be more than 5 characters';
        }
        if (strlen($password) > 29) {
            $errors[] = 'Your password must be less than 30 characters';
        }
        if ($password != $confirm_password) {
            $errors[] = 'Your passwords do not match';
        }

        if (!check_email($email)) {
            $errors[] = 'This email is already in use';
        }

        if (!check_username($username)) {
            $errors[] = 'This username is already in use';
        }


        if(!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert alert-danger" role="alert">
                         ' . $error . '
                      </div>';
            }
        } else {
            register_user($first_name, $last_name, $username, $email, $password);
            set_message("Check you email for activation link");
            header("Location: index.php");
        }
    }
}




?>