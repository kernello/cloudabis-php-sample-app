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
    <title>CloudABIS SDK WebForm</title>
    <style>
        body{
            margin: 0;
            padding: 0;
        }
        .homeContainer{
            display: flex;
            align-items: center;
            height: 100vh;
            justify-content: center;
            flex-direction: column;
            background-color: #ff9900;
        }
        .homeContainer div{
            background-color: #f5f5f5;
            width: 190px;
            margin-bottom: 10px;
            text-align: center;
        }
        .homeContainer div a{
            text-decoration: none;
            display: block;
            color: #ff9900;
            font-family: arial;
            font-size: 16px;
            transition: all .3s;
            padding: 12px 0px;
            border:1px solid #f5f5f5;
        }
        .homeContainer div a:hover{
            background-color: #ff9900;
            color: #fff;
        }
        .headline{
            text-align: center;
            margin-top: 0;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php 
        function LoadCloudABISToken()
        {
            // CloudABISAPICredentials cloudABISCredentials = new CloudABISAPICredentials();
            // cloudABISCredentials.AppKey = AppSettingsReader.CloudABISAppKey;
            // cloudABISCredentials.SecretKey = AppSettingsReader.CloudABISSecretKey;
            // cloudABISCredentials.BaseAPIURL = AppSettingsReader.CloudABIS_API_URL;
            // cloudABISCredentials.CustomerKey = AppSettingsReader.CloudABISCustomerKey;
            // cloudABISCredentials.EngineName = EngineName();

            //Read token from CloudABIS Server
            $cloudABISConnector = new CloudABISConnector(CloudABISAppKey, CloudABISSecretKey, CloudABIS_API_URL, CloudABISCustomerKey, ENGINE_NAME);

            $token = $cloudABISConnector->GetCloudABISToken();
            if ( ! is_null($token) && isset($token->access_token) != "" )
            {
                $_SESSION['access_token'] = $token->access_token;
                //SessionManager.CloudABISAPIToken = token.AccessToken;
                //SessionManager.CloudABISCredentials = cloudABISCredentials;
            }
            else
            {
                SetStatus("CloudABIS Not Authorized!. Please check credentails");
            }
        }

        LoadCloudABISToken();

        function SetStatus($message)
        {
            echo $message . "<br />";
        }
    ?>
    <div class="container homeContainer">
        <h1 class="headline">Welcome To CloudABIS web application</h1>
        <div><a href="IsRegister.php">IsRegistered</a></div>
        <div><a href="ChangeID.php">ChangeID</a></div>
        <div><a href="DeleteID.php">DeleteID</a></div>
        <div><a href="Register.php">Register</a></div>
        <div><a href="Identify.php">Identify</a></div>
        <div><a href="Verify.php">Verify</a></div>
        <div><a href="Update.php">Update</a></div>
        <div><a href="ActiveDevice.php">Change active device</a></div>
    </div>
</body>
</html>