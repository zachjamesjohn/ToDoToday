<?php
//functions.php
//COIS 3420 Culmination Project - Full Stack To-Do App
//Author: Zachary John - zjohn - 0593938
//Description: Miscellaneous functions for error checking and creating and logging in user

function emptyInputSignup($name, $email, $username, $password, $password2){ //Function for checking if there are any empty sign up fields
    if(empty($name)||empty($email)||empty($username)||empty($password)||empty($password2)){ //If any of the fields are empty
        header("location: signup.php/?error=emptyInput"); //Send error message to be printed to signup page
        exit();
    }
    else{
        return false; //return false because there is no error
    }
    
}

function invalidUsername($username){ //Function to check if the username is valid
 
    if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){ //If there are any character that are not from a-z or a number the username is invalid
        header("location: signup.php/?error=invalidUsername"); //Send error message to be printed to signup page
        exit();
    }
    else{
        return false; //return false because there is no error
    }
   
}

function invalidEmail($email){ //function to see if the email is valid
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ 
        header("location: signup.php/?error=invalidEmail"); //Send error message to be printed to signup page
        exit();
    }
    else{
        return false; //return false because there is no error
    }
}

function passwordMatch($password, $password2){ //Function to make sure the passwords match
    if($password !== $password2){ //If the password and repeated password don't match
        header("location: signup.php/?error=passwordMatch"); //Send error message to be printed to signup page
        exit();
    }
    else{ 
        return false; //return false because there is no error
    }
}

function usernameExists($conn, $username, $email){ //Check to see if the username exists. If it does return the row

    $sql = "SELECT * FROM admin WHERE username=? OR email=?;"; //Create the sql query to check for username or email already existing     //either 
    $stmt = mysqli_stmt_init($conn); //Create a prepared statement

    if(!mysqli_stmt_prepare($stmt, $sql)){ //Check to see if there is an error with the statement 
        header("location: signup.php/?error=stmterror"); //Send error message to be printed to signup page
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $username, $email); //Bind the parameters to the statement
    mysqli_stmt_execute($stmt); //Execute the prepared statement

    $resultData = mysqli_stmt_get_result($stmt); //Store the result data from the statement

    if ($row = mysqli_fetch_assoc($resultData)){ //If anything is returned then the username exists
        return $row; //Return the row
    }else{
        return false; //return null because there is no error
    }

    mysqli_stmt_close($stmt);//CLose the statement
}

function createUser($conn, $name, $email, $username, $password){ //Function to create the user 
    $sql = "INSERT INTO admin (firstname, email, username, password) VALUES (?, ?, ?, ?);"; //Create the sql query
    $stmt = mysqli_stmt_init($conn); //Create a prepared statement

    if(!mysqli_stmt_prepare($stmt, $sql)){ //Check to see if there is an error with the statement 
        header("location: signup.php/?error=failedToCreateUser"); //Send error message to be printed to signup page
        exit();
    }

    $hashedPwd = password_hash($password, PASSWORD_DEFAULT); //Hash the password

    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPwd); //Bind the parameters including the hashed password
    mysqli_stmt_execute($stmt); //Execute the statement
    mysqli_stmt_close($stmt); //Close the statement
    header("location: https://loki.trentu.ca/~zjohn/COIS_3420/Project/signin.php?message=createdUser"); //Send the user to the sign in page and print a message showing the signup was successful
    exit();

}

function emptyInputSignin( $username, $password){ //Function to check for an empty sign in field

    if(empty($username)||empty($password)){ //Check to see if either form field was empty
        header("location: signin.php/?error=emptyInput"); //Send error message to be printed to signin page
        exit();
    }
    else{
        return false; //return false because there is no error
    }

}

function loginUser($conn, $username, $password){ //Function to log in a user
    
    $usernameExists = usernameExists($conn, $username, $email); //Second use for check if username exists. saved the stored row

    if($usernameExists === false){ //Username does not exists
        header("location: signin.php?error=wrongLogin"); //Send error message to be printed to signin page
        exit();
    }

    $pwdHashed = $usernameExists["password"]; //Get the hashed password to check

    $checkPassword = password_verify($password, $pwdHashed); //Use a php hashing function to securely check if password is correct

    if($checkPassword === false){//Password is wrong
        header("location: signin.php?error=wrongPassword"); //Send error message to be printed to signin page
        exit();
    }else if ($checkPassword === true){ //Password is right
        session_start(); //Start the session
        $_SESSION["id"] = $usernameExists["id"]; //Save the userid 
        $_SESSION["name"] = $usernameExists["firstname"]; //Save the firstname
        $_SESSION['username'] = $usernameExists['username'];
        $_SESSION['showArchived'] = false; //Set showAchived to false by default
        header("location: https://loki.trentu.ca/~zjohn/COIS_3420/Project/homepage.php"); //Send the user to the homepage now that they are successfully logged in
        exit();
    }
}
