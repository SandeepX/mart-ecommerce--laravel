
<head>
    <title>Confirm Subscription | Alpasal</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Poppins', sans-serif; scroll-behavior: smooth; margin: 0px; padding: 0px;">

<section style="background-color: #0d578d;
	padding-top: 20px;
	height: 100%;
	width: 75%;
	margin: 0px auto;
	padding-bottom: 20px;">
    <div>
        <h1 style="color: #fff;
		    text-align: center;
		    letter-spacing: 2px;
		    font-weight: 600;
		    text-transform: uppercase;
		    margin-bottom: 20px;
		    margin-top: 0px;">WELCOME TO Alpasal</h1>
        <figure><img src="{{asset('default/images/logo.png')}}" style="max-width: 100%;
		    height: auto; margin: 0px auto;
			display: block;" alt=""></figure>
        <h2 style="color: #fff;
		    text-align: center;
		    letter-spacing: 2px;
		    font-weight: 600;
		    text-transform: uppercase;
		    margin: 5px 0px;">Confirm Subscription</h2>
        <p style="width: 60%;
			color: #fff;
			letter-spacing: 0.5px;
			font-size: 14px;
			font-weight: 500;
			margin: 15px auto;
			text-align: center;">
            Please click the button below to confirm the subscription.
        </p>
        <a href="{{$action_url}}" onMouseOver="this.style.color='#53ac53'"
           onMouseOut="this.style.color='#003574'" style="background-color: #fff;
			color: #003574;
			padding: 10px;
			margin: 15px auto;
			text-align: center;
			display: block;
			width: 200px;
			text-decoration: none;
			font-size: 16px;
			text-transform: uppercase;
			font-weight: 600;
			border-radius: 50px;
			box-shadow: 0 0 8px 0 rgb(9, 57, 93);">Confirm Subscription</a>
        <div style="clear: both"></div>
        <div style="text-align: center;
			margin-top: 20px;">
            <ul style="padding-left: 0px; ">
                <li style="list-style: none;
			display: inline-block;"><a href="javascript:void(0)" title="Facebook" >{{--<img src="{{asset('default/images/logo.png')}}" class="img-fluid" style="max-width: 100%;
		    height: auto; margin: 0px auto;
			display: block;" alt="">--}}</a></li>
                <li style="list-style: none;
			display: inline-block;"><a href="javascript:void(0)" title="Linkedin">{{--<img src="{{asset('default/images/logo.png')}}" class="img-fluid" style="max-width: 100%;
		    height: auto; margin: 0px auto;
			display: block;" alt="">--}}</a></li>
                <li style="list-style: none;
			display: inline-block;"><a href="javascript:void(0)" title="Twitter">{{--<img src="{{asset('default/images/logo.png')}}" class="img-fluid" style="max-width: 100%;
		    height: auto; margin: 0px auto;
			display: block;" alt="">--}}</a></li>
                <li style="list-style: none;
			display: inline-block;"><a href="javascript:void(0)" title="Instagram">{{--<img src="{{asset('default/images/logo.png')}}" class="img-fluid" style="max-width: 100%;
		    height: auto; margin: 0px auto;
			display: block;" alt="">--}}</a></li>
            </ul>
        </div>
    </div>
    <div style="clear: both;"></div>
    {{--<div style="text-align: center;">
        <ul style="padding-left: 0px;">
            <li style="list-style: none;
			display: inline-block;">
                <a href="index.php" onMouseOver="this.style.color='#53ac53'"
                   onMouseOut="this.style.color='#fff'" style="color: #fff;
			font-size: 14px;
			font-weight: 500;
			text-decoration: none;
			text-transform: uppercase;
			margin-right: 10px;">Home</a>
            </li>
            <li style="list-style: none;
			display: inline-block;">
                <a href="contact.php" onMouseOver="this.style.color='#53ac53'"
                   onMouseOut="this.style.color='#fff'" style="color: #fff;
			font-size: 14px;
			font-weight: 500;
			text-decoration: none;
			text-transform: uppercase;
			margin-right: 10px;">Contact Us</a>
            </li>
            <li style="list-style: none;
			display: inline-block;">
                <a href="about.php" onMouseOver="this.style.color='#53ac53'"
                   onMouseOut="this.style.color='#fff'" style="color: #fff;
			font-size: 14px;
			font-weight: 500;
			text-decoration: none;
			text-transform: uppercase;
			margin-right: 10px;">About Us</a>
            </li>
            <li style="list-style: none;
			display: inline-block;">
                <a href="list-property.php" onMouseOver="this.style.color='#53ac53'"
                   onMouseOut="this.style.color='#fff'" style="color: #fff;
			font-size: 14px;
			font-weight: 500;
			text-decoration: none;
			text-transform: uppercase;
			margin-right: 10px;">Property</a>
            </li>
            <li style="list-style: none;
			display: inline-block;">
                <a href="list-blog.php" onMouseOver="this.style.color='#53ac53'"
                   onMouseOut="this.style.color='#fff'" style="color: #fff;
			font-size: 14px;
			font-weight: 500;
			text-decoration: none;
			text-transform: uppercase;
			margin-right: 10px;">Blog</a>
            </li>
        </ul>
        <ul style="margin-bottom: 0px;
			padding-left: 0px;">
            <li style="display: block!important;
			color: #ececec;
			font-size: 18px;
			font-weight: 600;
			letter-spacing: 1px;">phone: {{$setting->phone}},{{$setting->mobile}}</li>
            <li style="display: block!important;
			color: #ececec;
			font-size: 18px;
			font-weight: 600;
			letter-spacing: 1px;">mail Us: <a href="mailto:{{$setting->email}}" style="color: #ececec;">{{$setting->email}}</a></li>
        </ul>
    </div>--}}
</section>

</body>









