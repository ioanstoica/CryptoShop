<?php

// Connect to the database using db_connect.php
require_once "db_connect.php";
$conn = db_connect();

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

// daca 'code' este setat, inseamna ca s-a trimis formularul cu codul de verificare
if (isset($_POST['code'])) {
    // get the form data
    $code = $_POST['code'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // read the random number from the text file
    $myfile = fopen("random.txt", "r") or die("Unable to open file!");
    $random = fread($myfile, filesize("random.txt"));
    fclose($myfile);

    // check if the code is correct
    if ($code == $random) {
        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the users table
        $sql = "INSERT INTO users (user, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "Signup successful! Welcome $username";
            session_start();
            $_SESSION["username"] = $username;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo 'Wrong verification code';
    }
} else {
    // Get the form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // generate a random number
    $random = rand(1000, 9999);
    // save the random number in a session variable
    // $_SESSION['random'] = $random;
    //store the random number in a text file
    $myfile = fopen("random.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $random);
    fclose($myfile);

    // Send an email to the user with the verification code
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.zoho.eu';
    $mail->SMTPAuth = true;
    $mail->Username = 'ioanstoica@zohomail.eu';
    $mail->Password = 'CcCc246...';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->SMTPDebug = 0;

    $mail->From = 'ioanstoica@zohomail.eu';
    $mail->FromName = 'Ioan Stoica';
    $mail->addAddress($email, $username);
    $mail->addReplyTo('ioanstoica@zohomail.eu', 'Ioan Stoica');

    $mail->isHTML(false);
    $mail->Subject = "Your verification code";
    $mail->Body    = "Your verification code is: " . $random;
    $mail->AltBody = 'Email body in plain text';

    if (!$mail->send()) {
        echo 'Verification code could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Verification code has been sent to your email address: ' . $email . '<br>';
    }


    // echo a form to enter the verification code
    echo '<form action="" method="post">
    <input type="text" name="code" placeholder="Enter the verification code">
    <input type="hidden" name="username" value="' . $username . '">
    <input type="hidden" name="email" value="' . $email . '">
    <input type="hidden" name="password" value="' . $password . '">
    <input type="submit" value="Verify">';
}


// Close the database connection
$conn->close();

echo '<br><br><a href="/index.php">Back</a>';
