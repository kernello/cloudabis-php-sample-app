var engineName = EnumEngines.FingerPrint;
/*
* Biometric Capture
*/
function captureBiometric() {
	// debugger
	document.getElementById('templateXML').value = '';
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
	document.getElementById('serverResult').style.display = 'block';
	if (captureResponse.CloudScanrStatus != null && captureResponse.CloudScanrStatus.Success) {

		if (captureResponse.TemplateData != null && captureResponse.TemplateData.length > 0) {
			document.getElementById('templateXML').value = PHPEncodeHTML(captureResponse.TemplateData);
		}
		else if (engineName == 'IRIS01' && captureResponse.BioImageData != null && captureResponse.BioImageData.length > 0) {
			document.getElementById('templateXML').value = captureResponse.BioImageData;
		}
		else {
			document.getElementById('lblTemplate').style.display = 'none';
		}
		document.getElementById('serverResult').innerHTML = "Capture success. Please click on identify button";
	}
	else if (captureResponse.CloudScanrStatus != null) {
		document.getElementById('serverResult').innerHTML = captureResponse.CloudScanrStatus.Message;
	} else {
		document.getElementById('serverResult').innerHTML = captureResponse;
	}
}