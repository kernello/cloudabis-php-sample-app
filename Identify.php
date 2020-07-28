<?php session_start();?>
<?php require 'vendor/autoload.php';?>
<?php require 'web.config.php';?>
<?php require 'CloudABIS/CloudABISConnector.php';?>
<?php use CloudABISSampleWebApp_CloudABIS\CloudABISConnector;?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Identify Form</title>
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
            min-width: 515px;
        }
        .commonForm label{
            display: inline-block;
            width: 255px;
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
            width: 66%;
        }
        #serverResult{
            background-color: #fff;
            padding: 10px 15px;
            width: 66%;
            text-align: center;
            margin-top: 15px;
        }
        .commonForm input[type="text"]{
            padding: 14px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .commonForm select{
            padding: 14px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
        }
        .commonForm input[type="submit"],
        .commonForm input[type="button"]{
            border-radius: 5px;
            padding: 12px 0px;
            cursor: pointer;
            margin: 0px;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border:1px solid #f5f5f5;
            transition: all .3s;
            color: #ff9900;
        }
        .commonForm input[type="submit"]:hover,
        .commonForm input[type="button"]:hover{
            background-color: #ff9900;
            color: #fff;
        }
        .mt-10{margin-bottom:10px;}
    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
    <script src="scripts/CloudABIS-ScanR.js"></script>
	<script src="scripts/CloudABIS-Helper.js"></script>
</head>
<body>
    <div class="formWrapper">
        <form class="commonForm" action="" method="POST">
            <h1 class="headline">Identity</h1>
            <div class="mt-10">
                <label>Capture Type:</label>
                <select name="captureType" id="captureType">
                    <option value="SingleCapture">Single Capture</option>
                    <option value="DoubleCapture">Double Capture</option>
                </select>
            </div>
            <div class="mt-10">
                <label>Quick Scan:</label>
                <select name="quickScan" id="quickScan">
                    <option value="Enable">Enable</option>
                    <option value="Disable">Disable</option>
                </select>
            </div>
            <div>
                <label id="lblCurrentDeviceName">Current Device Name:</label>
                <input type="button" name="biometricCapture" value="Biometric Capture" onclick="captureBiometric('Identify')">
                <input type="submit" name="identify" value="Identify">
            </div>
            <input type="button" value="Back" onClick="javascript:backToHome()">
            <input type="hidden" name="templateXML" id="templateXML" value="">
        </form>
        <label id="serverResult"></label>

        <?php
        if (isset($_POST['identify'])) {
            if ($_POST['identify'] != "") {
                $templateXML = $_POST['templateXML'];
                if (isset($_COOKIE['CSTempalteFormat'])) {
                    $templateFormat = $_COOKIE['CSTempalteFormat'];
                }

                try
                {
                    if ($templateXML != "") {
                        if (isset($_SESSION['access_token']) && $_SESSION['access_token'] != "") {
                            $cloudABISConnector = new CloudABISConnector(CloudABISAppKey, CloudABISSecretKey, CloudABIS_API_URL, CloudABISCustomerKey, ENGINE_NAME);
                            $lblMessageText = $cloudABISConnector->Identify($templateXML, $_SESSION['access_token'], $templateFormat);
                            SetStatus($lblMessageText);
                        } else {
                            header("location: Login.php");
                        }
                    } else {
                        SetStatus("Problem in biometric template data");
                    }
                } catch (Exception $ex) {
                    SetStatus($ex->Message());
                }
            } else {
                SetStatus("Please put registration id");
            }
        }

        function SetStatus($message)
        {
            echo '<h3 class="sresponse"> Server response: '.$message.'</h3>';
        }
        ?>
    </div>
    <script>
        document.getElementById('serverResult').style.display = 'none';
        function backToHome() {
            window.location.href = "index.php";
        }
    </script>
</body>
</html>