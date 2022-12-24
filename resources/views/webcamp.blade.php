<!DOCTYPE html>  
<head>  
    <title>QRCode相機</title>  
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>  
<!-- CSS -->
<style>
#my_camera{
    width: 100%;
    height: 500px;
    border: 1px solid black;
}
</style>

<div id="my_camera"></div>
{{-- <input type=button value="開啟相機" onClick="take_snapshot()"> --}}
 
<div id="results" ></div>
 
<!-- Webcam.min.js -->
<script type="text/javascript" src="webcamjs/webcam.min.js"></script>

<!-- Configure a few settings and attach camera -->
<script language="JavaScript">
    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
    });
    Webcam.attach( '#my_camera' );

function take_snapshot() {
 
    // take snapshot and get image data
    Webcam.snap( function(data_uri) {
        // display results in page
        document.getElementById('results').innerHTML = 
        '<img src="'+data_uri+'"/>';
    } );
}
</script>

