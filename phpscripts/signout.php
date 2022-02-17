<?php
//signout.php
//COIS 3420 Culmination Project - To-Do App
//Author: Zachary John - zjohn - 0593938
//Description: Script for signing out the user


if(isset($_GET["message"])){ //If the message is passed telling the script to sign out the user

    session_unset(); 
    session_destroy(); //Destroy session
    header("location: ../signin.php?message=loggedOut"); //Pass the successful sign out message to the sign in page to be displayed
    exit();
}
