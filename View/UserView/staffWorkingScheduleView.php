<?php
    /*Author Name: Chew Wei Seng*/
    require_once __DIR__ . '/../../Config/webConfig.php';
    $webConfig = new webConfig();
    $webConfig->restrictAccessForNonLoggedInStaff();//only allow the logged in staff to access
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        
        <h1 style="margin-top: 50px;">Your Working Schedule And Attendance</h1>
        
    </body>
</html>
