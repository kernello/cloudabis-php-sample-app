<?php session_start(); ?>
<?php require 'vendor/autoload.php'; ?>
<?php require 'web.config.php'; ?>
<?php require 'CloudABIS/CloudABISConnector.php'; ?>
<?php use CloudABISSampleWebApp_CloudABIS\CloudABISConnector; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IsRegister Form</title>
    <style>
        body{
            margin: 0;
            padding: 0;
        }
        .formWrapper{
            display: flex;
            justify-content: center;
            height: 100vh;
            align-items: center;
            flex-flow: column;
            background-color: #ff9900;
        }
        .commonForm{
            border: 1px solid #f5f5f5;
            padding: 50px;
        }
        .commonForm label{
            color: #fff;
        }
        .headline{
            text-align: center;
            margin-top: 0;
            color: #fff;
        }
        .sresponse{
            background-color: #fff;
            padding: 15px;
            text-align: center;
            width: 28%;
        }
        .commonForm input[type="text"]{
            padding: 14px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .commonForm input[type="submit"],
        .commonForm input[type="button"]{
            border-radius: 5px;
            padding: 12px 10px;
            cursor: pointer;
            margin: 5px;
            width: 90px;
            color: #ff9900;
            background-color: #fff;
            border:1px solid #f5f5f5;
            transition: all .3s;
        }
        .commonForm input[type="submit"]:hover,
        .commonForm input[type="button"]:hover{
            background-color: #ff9900;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="formWrapper">
        <form class="commonForm" action="" method="POST">
            <h1 class="headline">Checking an ID already exist or not</h1>
            <label for="ID">ID:</label>
            <input type="text" name="txtID" id="txtID" value="">
            <input type="submit" name="submit" value="IsRegister">
            <input type="button" value="Back" onClick="javascript:backToHome()">
        </form>

        <?php 
            function SetStatus($message)
            {
                echo '<h3 class="sresponse"> Server response: '.$message.'</h3>';
            }
        ?>

        <?php 
            if ( isset($_POST['submit']) ) {
                if ( $_POST['txtID'] != "" ) {
                    $regID = $_POST['txtID'];
                    try
                    {
                        if ( $regID != "" )
                        {
                            $regID = trim($regID);

                            if ( isset($_SESSION['access_token']) && $_SESSION['access_token'] != "" ) 
                            {
                                $cloudABISConnector = new CloudABISConnector(CloudABISAppKey, CloudABISSecretKey, CloudABIS_API_URL, CloudABISCustomerKey, ENGINE_NAME);
                                
                                $lblMessageText = $cloudABISConnector->IsRegister($regID, $_SESSION['access_token']);
                                SetStatus($lblMessageText);
                            }
                            else
                            {
                                header("location: Login.php");
                            }
                        }
                        else SetStatus("Please give an ID");
                    }
                    catch (Exception $ex)
                    {
                        SetStatus($ex->Message());
                    }
                }
                else {
                    SetStatus("Please put registration id");
                }
            }
        ?>
    </div>
    <script>
        function backToHome() {
            window.location.href = "index.php";
        }
    </script>
</body>
</html>