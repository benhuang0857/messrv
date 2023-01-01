<html>
<head>
    <meta charset="utf-8">
    <title>壽山掃條碼測試</title>
</head>
<body>
    <!-- 條碼顯示的內容顯示在 qr-reader-results -->
    <div id="qr-reader-results"><h1 id="qrtext"></h1></div>
	<!-- 掃描用 -->
    <div id="qr-reader" style="width:100%"></div>    
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.0.3/html5-qrcode.min.js"></script>
<script>
    <!-- 呼叫 html5qrcode 代入qr-reader -->
	const html5QrCode = new Html5Qrcode("qr-reader");
	const qrCodeSuccessCallback = (decodedText, decodedResult) => {
          /* 處理成功掃描 */  
		  document.getElementById("qrtext").innerHTML = decodedText;
	      if (decodedText.toUpperCase().startsWith("SMSTO:1922:")) {
              var t = decodedText.split(":", 3),
              r= t[1],
              a= t[2],
		      o = "sms:" + r + "&body=" + a; // to-do 如果是android 要將&改為?
			  window.location.href = o;
		  }
          html5QrCode.clear();
     };
     const config = { fps: 10, qrbox: 800 };
	// 直接開啟後面鏡頭
	<!-- 開啟前置鏡頭 -->
	<!-- html5QrCode.start({ facingMode: "user" }, config, qrCodeSuccessCallback); -->
	html5QrCode.start({ 
	  facingMode: "environment" }, 
	  config, qrCodeSuccessCallback);
</script>
</head>
</html>