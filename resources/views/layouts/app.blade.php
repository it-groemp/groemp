<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<link href="{{asset('css/home.css')}}" rel="stylesheet">
		<link href="{{asset('images/logo.png')}}"rel="icon" type="image/icon type">
		<style type="text/css">
			a.btn-social, .btn-social
			{
				border-radius: 50%;
				color: #ffffff !important;
				display: inline-block;
				height: 54px;
				line-height: 54px;
				margin: 8px 4px;
				text-align: center;
				text-decoration: none;
				transition: background-color .3s;
				webkit-transition: background-color .3s;
				width: 54px;
			}

			.btn-social .fa,.btn-social i
			{
				backface-visibility: hidden;
				moz-backface-visibility: hidden;
				ms-transform: scale(1);
				o-transform: scale(1);
				transform: scale(1);
				transition: all .25s;
				webkit-backface-visibility: hidden;
				webkit-transform: scale(1);
				webkit-transition: all .25s;
			}

			.btn-social:hover,.btn-social:focus
			{
				color: #fff;
				outline: none;
				text-decoration: none;
			}

			.btn-social:hover .fa,.btn-social:focus .fa,.btn-social:hover i,.btn-social:focus i
			{
				ms-transform: scale(1.3);
				o-transform: scale(1.3);
				transform: scale(1.3);
				webkit-transform: scale(1.3);
			}

			.btn-youtube
			{
				background-color: #E52D27;
				font-size: 30px;
			}

			.btn-youtube:hover
			{
				background-color: #EA5955;
			}
		</style>
		<title>@yield('pageTitle')</title>
        @yield('css')
	</head>
    <body>
        @include('layouts.header')		
			<button type="button" class="btn btn-floating btn-lg" id="btn-back-to-top">
				<i class="fa fa-angle-up"></i>
			</button>
		@yield('content')
		@include('layouts.footer')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		<script type="text/javascript">
			//Get the button
			let mybutton = document.getElementById("btn-back-to-top");

			// When the user scrolls down 20px from the top of the document, show the button
			window.onscroll = function () {
				scrollFunction();
			};

			function scrollFunction() {
				if(document.body.scrollTop>20 || document.documentElement.scrollTop > 20){
					mybutton.style.display = "block";
				}
				else{
					mybutton.style.display = "none";
				}
			}
			// When the user clicks on the button, scroll to the top of the document
			mybutton.addEventListener("click", backToTop);

			function backToTop() {
				document.body.scrollTop = 0;
				document.documentElement.scrollTop = 0;
			}
		</script>
		@yield('js')
    </body>