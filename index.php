
<html>
	<head>
		<title> too cool for a title!   </title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
  	<style>
    p {
  text-align: right;
  /* margin-left: auto;
  margin-right: auto;
  width: 22%; */
      font-size:50px;font-family:elephant;font-style:italic:;color:white;
    }
		body {
	margin: 0;
	padding: 0;
}
			body:before{
	background-image:url('pexels-markus-spiske-2004161.jpg');
	content: '';
	position: fixed;
	width: 100vw;
	height: 100vh;
	background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
      -webkit-filter: blur(4px);
     -moz-filter: blur(4px);
    -o-filter: blur(4px);
    -ms-filter: blur(4px);
     filter: blur(4px);
      z-index: -9;
			/* width: auto; */
			 margin:0;
			 /* padding:0; */
			}
			.white-box1 {
				margin-left:180;
    background-color: #0bedae;
    color: white;
    height: 160;
		width: 160;
		font-family:elephant;font-style:italic:;font-size:25px;
}
.white-box2 {
	float:left;
background-color: #09BC8A;
color: white;
height: 160;
width: 160;
font-family:elephant;font-style:italic:;font-size:15px;
}
.white-box3 {
	display: inline-block;
		float:right;
background-color: #09BC8A;

color: white;
height: 160;
width: 160;
font-family:elephant;font-style:italic:;font-size:15px;
}
.white-box4 {
	float:left;
background-color: #0bedae;
color: white;
height: 160;
width: 160;
font-family:elephant;font-style:italic:;font-size:15px;
}
.white-box5 {
	float:left;
background-color: #09BC8A;
color: white;
height: 160;
width: 160;
font-family:elephant;font-style:italic:;font-size:15px;
}
i{
	float:right;
	margin-right:240;
}
j{
	float:left;
	margin-left:332;
}
z{
	float:left;
}
.white-box3:hover{
	background-color:red;
	height: 200;
	width: 200;
}
.white-box2:hover{
	background-color:red;
	height: 200;
	width: 200;
}
.white-box4:hover{
	background-color:red;
	height: 200;
	width: 200;
}
.white-box5:hover{
	background-color:red;
	height: 200;
	width: 200;
}
		</style>
		<script>
		function signIn() {
		    var email = document.getElementById("email").value;
		    var password = document.getElementById("password").value;

		    if (email != '' && password != '') {
		        // Both fields are filled, navigate to the other page
		      location.href = 'main.php';
		    }
		}
		</script>
	</head>
	<body>
		<div class="white-box1">
			<center><br>
		    Project 2: femTomas
			</center>
		</div>
		<i>
		<img src="pexels-digital-buggu-374563.jpg"  width=160 height= 160 />
	</i>
	<a href="Project2Description.pdf">
		<div class="white-box3" id = "h1">
			<center><br>
				<img src ="1947310-200.png" height = 80 width=80 /><br>
LEARN MORE
			</center>
		</div>
	</a>
   <P>         Tomasulo Algorithm Simulation
  </P>
	<j>
		<img src = "pexels-kevin-ku-577585.jpg" width = 160 height = 160>
	</j>
	<a href="choice.php">
	<div class="white-box2" id = "h2">
		<center><br>
			<img src ="Apps-Run-icon.png" height = 80 width=80 /><br>
RUN PROGRAM
		</center>
	</div>
</a>
<a href = "Project 2 report.pdf">
	<div class="white-box4" id = "h3">
		<center><br>
			<img src ="1944731.png" height = 80 width=80 /><br>
DOWNLOAD PROJECT REPORT
		</center>
	</div>
</a>
	<z>
		<img src = "pexels-negative-space-160107.jpg" width = 160 height = 160>
		<img src = "pexels-athena-2582937.jpg" width = 160 height = 160>
	</z>
	<a href = "Codes.zip">
	<div class="white-box4" id = "h4">
		<center><br>
			<img src ="source-code-icon-6.jpg" height = 80 width=80 /><br>
DOWNLOAD SOURCE CODES
		</center>
	</div>
</a>

	</body>
</html>
