<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{route('home')}}">
                <img src="images/logo.png" width="80px" height="80px" alt="Logo"/>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item" >
                        <a class="nav-link<?php echo Route::currentRouteName()=='about-us'?' active':''?>" href="{{route('about-us')}}">About Us</a>
                    </li>
                    <li class="nav-item" id="contact-us">
                        <a class="nav-link<?php echo Route::currentRouteName()=='contact-us'?' active':''?>" href="">Contact Us</a>
                    </li>
                    <li class="nav-item" id="our-brands">
                        <a class="nav-link<?php echo Route::currentRouteName()=='our-benefits'?' active':''?>" href="{{route('our-benefits')}}">Our Partners</a>
                    </li>
                </ul>
                @if(Session::has("employee"))
                    <div class="d-flex">				
                        <a class="btn btn-colored" href="{{route('logout')}}" style="padding: 10px 20px; margin-right: 20px;">Logout</a>
                    </div>
                @else
                    <div class="d-flex">				
                        <a class="btn btn-colored" href="{{route('login')}}" style="padding: 10px 20px; margin-right: 20px;">Login</a>
                    </div>
                @endif

            </div>
        </div>
    </nav>
</header>