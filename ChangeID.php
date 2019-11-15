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
    <title>ChangeID Form</title>
    <style>
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
            display: block;
            margin-bottom: 10px;
            margin-top: 10px;
            text-align: center;
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
            width: 95%;
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
    </style>
</head>
<body>
    <div class="formWrapper">
        <form class="commonForm" action="" method="POST">
            <h1 class="headline">Change ID</h1>
            <div>
                <label for="oldID">Old ID</label>
                <input type="text" name="oldID" id="oldID" value="">
            </div>
            <div>
                <label for="newID">New ID</label>
                <input type="text" name="newID" id="newID" value="">
            </div>
            <div>
                <input type="submit" name="change" value="Change ID">
            </div>
            <input type="button" value="Back" onClick="javascript:backToHome()">
        </form>
        <label id="serverResult"></label>

        <?php
        if (isset($_POST['change'])) {
            if ($_POST['change'] != "") {
                $oldID = $_POST['oldID'];
                $newID = $_POST['newID'];

                try
                {
                    if ($oldID != "" && $newID != "") {
                        if (isset($_SESSION['access_token']) && $_SESSION['access_token'] != "") {
                            $cloudABISConnector = new CloudABISConnector(CloudABISAppKey, CloudABISSecretKey, CloudABIS_API_URL, CloudABISCustomerKey, ENGINE_NAME);
                            $lblMessageText = $cloudABISConnector->ChangeID($oldID, $newID, $_SESSION['access_token']);
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
        function backToHome() {
            window.location.href = "index.php";
        }
    </script>
</body>
</html>