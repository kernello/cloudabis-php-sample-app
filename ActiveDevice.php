<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Active Device</title>
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
        .iholder{
            border: 1px solid #f5f5f5;
            padding: 50px;
            width: 515px;
        }
        .iholder label{
            display: inline-block;
            width: 150px;
            color: #fff;
        }
        .headline{
            text-align: center;
            margin-top: 0;
            color: #fff;
        }
        .iholder input[type="text"]{
            padding: 14px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .iholder select{
            padding: 14px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
        }
        .iholder input[type="submit"],
        .iholder button,
        .iholder input[type="button"]{
            border-radius: 5px;
            padding: 12px 0px;
            cursor: pointer;
            margin: 0px;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border:1px solid #f5f5f5;
            transition: all .3s;
            color:#ff9900;
        }
        .iholder button:hover,
        .iholder input[type="button"]:hover{
            background-color: #ff9900;
            color: #fff;
        }
        .mt-10{margin-bottom:10px;}
    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        function setConfiguration() {
            var engineName = document.getElementById("engineName");
            engineName = engineName.options[engineName.selectedIndex].value;
            var templateFormat = document.getElementById("templateFormat");
            templateFormat = templateFormat.options[templateFormat.selectedIndex].value;

            var deviceName = document.getElementById("deviceName");
            deviceName = deviceName.options[deviceName.selectedIndex].value;

            if ( engineName != '' ) {
                //set credentials in cookey or any others client storage or get your storage
                setCookie("CSDeviceName", deviceName, 7);
                setCookie("CABEngineName", engineName, 7);
                setCookie("CSTempalteFormat", templateFormat, 7);

                window.location.href = "index.php";
            } else {
                failCall("Please put required values.");
            }

        }

        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }
        function failCall(status) {
            document.getElementById('lblMessage').innerHTML = status;
        }
    </script>
</head>
<body>
<div class="formWrapper">
    <div class="iholder">
        <h1 class="headline">Active Device</h1>
        <label for="deviceName">Device Name</label>
        <select id="deviceName">
            <option value="Secugen">Secugen</option>
            <option value="TwoPrintFutronic">TwoPrintFutronic</option>
            <option value="TenPrintFutronic">TenPrintFutronic</option>
            <option value="DigitalPersona">DigitalPersona</option>
            <option value="TwoPrintWatsonMini">TwoPrintWatsonMini</option>
            <option value="TenPrintWatsonMini">TenPrintWatsonMini</option>
            <option value="HitachiFV">HitachiFV</option>
            <option value="CMitech">CMitech</option>
            <option value="Face">Face</option>
        </select>
        <label for="engineName">Engine Name</label>
        <select id="engineName">
            <option value="FPFF02">FingerPrint</option>
            <option value="FVHT01">FingerVein</option>
            <option value="IRIS01">Iris</option>
            <option value="FACE01">Face</option>
        </select>
        <label for="templateFormat">Template Format</label>
        <select id="templateFormat">
            <option value="ISO">ISO</option>
            <option value="ICS">ICS</option>
            <option value="ANSI">ANSI</option>
            <option value="FP2">FP2</option>
            <option value="FP1">FP1</option>
            <option value="M2ICS">M2ICS</option>
        </select>
        <button onClick="javascript:setConfiguration()">Set Active Device</button>
        <input type="button" value="Back" onClick="javascript:backToHome()">
        <label id="lblMessage"></label>
    </div>
</div>
    <script>
        function backToHome() {
            window.location.href = "index.php";
        }
    </script>
</body>
</html>