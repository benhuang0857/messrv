<!DOCTYPE html>  
<head>  
    <title>HTML5 GetUserMedia Demo</title>  
	 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />  
</head>  
<body>  
    <input type="button" title="開啟攝像頭" value="開啟攝像頭" onclick="getMedia();" /><br />  
    <video height="120px" autoplay="autoplay"></video><hr />  
    <input type="button" title="拍照" value="拍照" onclick="getPhoto();" /><br />  
    <canvas id="canvas1" height="120px" ></canvas><hr />  
    <input type="button" title="視訊" value="視訊" onclick="getVedio();" /><br />  
    <canvas id="canvas2" height="120px"></canvas>  
  
    <script type="text/javascript">  
        var video = document.querySelector('video');  
        var audio, audioType;  
  
        var canvas1 = document.getElementById('canvas1');  
        var context1 = canvas1.getContext('2d');  
  
        var canvas2 = document.getElementById('canvas2');  
        var context2 = canvas2.getContext('2d');  
  
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;  
        window.URL = window.URL || window.webkitURL || window.mozURL || window.msURL;  
  
        var exArray = []; //儲存裝置源ID  
        MediaStreamTrack.getSources(function (sourceInfos) {  
            for (var i = 0; i != sourceInfos.length; ++i) {  
                var sourceInfo = sourceInfos[i];  
                //這裡會遍歷audio,video，所以要加以區分  
                if (sourceInfo.kind === 'video') {  
                    exArray.push(sourceInfo.id);  
                }  
            }  
        });  
  
        function getMedia() {  
            if (navigator.getUserMedia) {  
                navigator.getUserMedia({  
                    'video': {  
                        'optional': [{  
                            'sourceId': exArray[1] //0為前置攝像頭，1為後置  
                        }]  
                    },  
                    'audio':true  
                }, successFunc, errorFunc);    //success是獲取成功的回撥函式  
            }  
            else {  
                alert('Native device media streaming (getUserMedia) not supported in this browser.');  
            }  
        }  
  
        function successFunc(stream) {  
            //alert('Succeed to get media!');  
            if (video.mozSrcObject !== undefined) {  
                //Firefox中，video.mozSrcObject最初為null，而不是未定義的，我們可以靠這個來檢測Firefox的支援  
                video.mozSrcObject = stream;  
            }  
            else {  
                video.src = window.URL && window.URL.createObjectURL(stream) || stream;  
            }  
  
            //video.play();  
  
            // 音訊  
            audio = new Audio();  
            audioType = getAudioType(audio);  
            if (audioType) {  
                audio.src = 'polaroid.' + audioType;  
                audio.play();  
            }  
        }  
        function errorFunc(e) {  
            alert('Error！'+e);  
        }  
  
          
        // 將視訊幀繪製到Canvas物件上,Canvas每60ms切換幀，形成肉眼視訊效果  
        function drawVideoAtCanvas(video,context) {  
            window.setInterval(function () {  
                context.drawImage(video, 0, 0,90,120);  
            }, 60);  
        }  
  
        //獲取音訊格式  
        function getAudioType(element) {  
            if (element.canPlayType) {  
                if (element.canPlayType('audio/mp4; codecs="mp4a.40.5"') !== '') {  
                    return ('aac');  
                } else if (element.canPlayType('audio/ogg; codecs="vorbis"') !== '') {  
                    return ("ogg");  
                }  
            }  
            return false;  
        }  
  
        // vedio播放時觸發，繪製vedio幀影象到canvas  
//        video.addEventListener('play', function () {  
//            drawVideoAtCanvas(video, context2);  
//        }, false);  
  
        //拍照  
        function getPhoto() {  
            context1.drawImage(video, 0, 0,90,120); //將video物件內指定的區域捕捉繪製到畫布上指定的區域，實現拍照。  
        }  
  
        //視訊  
        function getVedio() {  
            drawVideoAtCanvas(video, context2);  
        }  
  
    </script>  
</body>  
