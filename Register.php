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
    <title>Registration Form</title>
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
            width: 515px;
        }
        .commonForm label{
            display: block;
            width: 215px;
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
            width:95%;
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
    <script type="text/javascript">        
        var engineName = EnumEngines.FingerPrint;
        /*
         * Biometric Capture
         */
        function captureBiometric() {
            // debugger
            document.getElementById('templateXML').value = '';
            //document.getElementById('templateXML').style.display = 'none';
            document.getElementById('serverResult').style.display = 'none';
            document.getElementById('serverResult').innerHTML = '';
            $('.sresponse').hide();

            var deviceName = getCookieValue("CSDeviceName");
            var templateFormat = getCookieValue("CSTempalteFormat");
            engineName = getCookieValue("CABEngineName");
            document.getElementById('lblCurrentDeviceName').innerHTML = 'Current Device Name: ' + deviceName;

            var apiPath = "http://localhost:15896/";

            //Init CloudABIS Scanr
            CloudABISScanrInit(apiPath);
            var captureType = document.getElementById("captureType");
            captureType = captureType.options[captureType.selectedIndex].value;
            var quickScan = EnumFeatureMode.Disable;

            /*API Call*/
            if (engineName == EnumEngines.FingerPrint) {
                console.log(captureType);
                FingerPrintCapture(deviceName, quickScan, templateFormat, captureType, EnumCaptureMode.TemplateOnly, EnumBiometricImageFormat.WSQ,
                    EnumSingleCaptureMode.LeftFingerCapture, 180.0, EnumCaptureOperationName.ENROLL, CaptureResult);
            }
            
            else if (engineName == EnumEngines.FingerVein)
                FingerVeinCapture(deviceName, quickScan, captureType, 180.0, EnumCaptureOperationName.ENROLL, CaptureResult);
            else if (engineName == EnumEngines.Iris)
                IrisCapture(deviceName, quickScan, 180.0, EnumFeatureMode.Disable, CaptureResult);
            else if (engineName == EnumEngines.Face)
                FaceCapture(quickScan, 180.0, EnumFeatureMode.Disable, EnumFaceImageFormat.Jpeg, EnumCaptureOperationName.ENROLL, CaptureResult);
        }

        function getCookieValue(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        /*
        * Hnadle capture data
        */
        function CaptureResult(captureResponse) {
            // debugger
            document.getElementById('serverResult').style.display = 'block';
            if (captureResponse.CloudScanrStatus != null && captureResponse.CloudScanrStatus.Success) {

                if (captureResponse.TemplateData != null && captureResponse.TemplateData.length > 0) {
                    document.getElementById('templateXML').value =PHPEncodeHTML(captureResponse.TemplateData);
                }
                else if (engineName == 'IRIS01' && captureResponse.BioImageData != null && captureResponse.BioImageData.length > 0) {
                    document.getElementById('templateXML').value = PHPEncodeHTML(captureResponse.BioImageData);
                }
                else {
                    document.getElementById('lblTemplate').style.display = 'none';
                    //document.getElementById('templateXML').style.display = 'none';
                }
                document.getElementById('serverResult').innerHTML = "Capture success. Please click on identify button";
            }
            else if (captureResponse.CloudScanrStatus != null) {
                document.getElementById('serverResult').innerHTML = captureResponse.CloudScanrStatus.Message;
            } else {
                document.getElementById('serverResult').innerHTML = captureResponse;
            }
        }
    </script>
</head>
<body>
    <div class="formWrapper">
        <form class="commonForm" action="" method="POST">
            <h1 class="headline">Registration</h1>
            <div class="mt-10">
                <label>Capture Type:</label>
                <select name="captureType" id="captureType">
                    <option value="SingleCapture">Single Capture</option>
                    <option value="DoubleCapture">Double Capture</option>
                </select>
            </div>
            <div>
                <label for="registrationID">Registration ID</label>
                <input type="text" name="registrationID" id="registrationID" value="">
            </div>
            <div>
                <label id="lblCurrentDeviceName">Current Device Name:</label>
                <input type="button" name="biometricCapture" value="Biometric Capture" onclick="captureBiometric()">
                <input type="submit" name="register" value="Register">
            </div>
            <input type="button" value="Back" onClick="javascript:backToHome()">
            <input type="hidden" name="templateXML" id="templateXML" value="">
        </form>
        <label id="serverResult"></label>

        <?php
        if (isset($_POST['register'])) {
            if ($_POST['registrationID'] != "") {
                $regID = $_POST['registrationID'];
                $templateXML = $_POST['templateXML'];
                if (isset($_COOKIE['CSTempalteFormat'])) {
                    $templateFormat = $_COOKIE['CSTempalteFormat'];
                }

                try
                {
                    if ($regID != "" && $templateXML != "") {
                        $regID = trim($regID);
                        if (isset($_SESSION['access_token']) && $_SESSION['access_token'] != "") {
                            $cloudABISConnector = new CloudABISConnector(CloudABISAppKey, CloudABISSecretKey, CloudABIS_API_URL, CloudABISCustomerKey, ENGINE_NAME);
                            $lblMessageText = $cloudABISConnector->Register($templateXML, $regID, $_SESSION['access_token'], $templateFormat);
                            SetStatus($lblMessageText);
                        } else {
                            header("location: Login.php");
                        }
                    } else {
                        SetStatus("Please give an ID");
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