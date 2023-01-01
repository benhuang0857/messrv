<!DOCTYPE html>
<html>
<head>
    <title>Instascan</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/instascan/1.0.0/index.js" integrity="sha512-QblNATV/gin5FC8tqTM2gfCMBei2qCzTte4O6CxGp8KQ5BgC5vNNGv99uTBvzmq+AFFYFoUNhowGOOJNTIBy6A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- 載入instascan.min.js -->
</head>

<body>
    <video id="preview"></video>
    <!-- 詢問是否允許開啟相機後，會顯示在這個元素裡 -->
    <!-- ---------- -->
    <!-- 以下程式面 -->
    <script type="text/javascript">
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview')
    });
    // 開啟一個新的掃描
    // 宣告變數scanner，在html<video>標籤id為preview的地方開啟相機預覽。
    // Notice:這邊注意一定要用<video>的標籤才能使用，詳情請看他的github API的部分解釋。

    scanner.addListener('scan', function(content) {
        console.log(content);
    });
    //開始偵聽掃描事件，若有偵聽到印出內容。

    Instascan.Camera.getCameras().then(function(cameras) {
    //取得設備的相機數目
        if (cameras.length > 0) {
          ///若設備相機數目大於0 則先開啟第0個相機(程式的世界是從第零個開始的)
            scanner.start(cameras[0]);
        } else {
          //若設備沒有相機數量則顯示"No cameras found";
          //這裡自行判斷要寫什麼
            console.error('No cameras found.');
        }
    }).catch(function(e) {
        console.error(e);
    });
    </script>
</body>
</html>