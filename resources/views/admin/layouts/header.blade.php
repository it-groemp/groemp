<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{route('admin-login')}}">
                <img src="../images/logo.png" width="80px" height="80px" alt="Logo"/>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @if(Session::has("role"))
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @php
                            $company = ["company-details-admin","company-details-employer","cc-details","workflow-details", "display-admin","company-benefit-details"];
                            $employee = ["employee-details","employee-benefits-admin"];
                            $admin = ["benefit-details","category-details"];
                            $route = Route::currentRouteName();
                        @endphp
                        <li class="nav-item" >
                            <a class="nav-link<?php echo in_array($route,$company)?' active':''?>" href="{{Session::get('role')=='Admin' ? route('company-details-admin') : route('company-details-employer')}}">Company</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?php echo in_array($route,$employee)?' active':''?>" href="{{route('employee-details')}}">Employee</a>
                        </li>
                        @if(Session::get("role")=="Admin")
                            <li class="nav-item">
                                <a class="nav-link<?php echo  in_array($route,$admin)?' active':''?>" href="{{route('benefit-details')}}">Admin</a>
                            </li>
                        @endif
                    </ul>
                    <div class="d-flex">				
                        <a class="btn btn-colored" href="{{route('admin-logout')}}" style="padding: 10px 20px; margin-right: 20px;">Logout</a>
                    </div>
                </div>
            @endif
        </div>
    </nav>
    @if(Session::has("admin_id") && Session::get("role")=="Admin")
        <ul class="nav nav-tabs">
            @if(in_array($route,$company))
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='company-details-admin'?' active':''?>" aria-current="page" href="{{route('company-details-admin')}}">Company Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='cc-details'?' active':''?>" aria-current="page" href="{{route('cc-details')}}">Cost Center Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='workflow-details'?' active':''?>" aria-current="page" href="{{route('workflow-details')}}">Workflow Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='display-admin'?' active':''?>" aria-current="page" href="{{route('display-admin')}}">Admin Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='company-benefit-details'?' active':''?>" aria-current="page" href="{{route('company-benefit-details')}}">Company Benefit Details</a>
                </li>
            @elseif(in_array($route,$employee))            
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='employee-details'?' active':''?>" aria-current="page" href="{{route('employee-details')}}">Employee Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='employee-benefits-admin'?' active':''?>" aria-current="page" href="{{route('employee-benefits-admin')}}">Employee Benefits Details</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='benefit-details'?' active':''?>" aria-current="page" href="{{route('benefit-details')}}">Benefit Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='category-details'?' active':''?>" aria-current="page" href="{{route('category-details')}}">Category Details</a>
                </li>
            @endif
        </ul>
    @elseif(Session::has("admin_id") && Session::get("role")=="Employer")
        <ul class="nav nav-tabs">
            @if(in_array($route,$company))
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='company-details-employer'?' active':''?>" aria-current="page" href="{{route('company-details-employer')}}">Company Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='cc-details'?' active':''?>" aria-current="page" href="{{route('cc-details')}}">Cost Center Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='workflow-details'?' active':''?>" aria-current="page" href="{{route('workflow-details')}}">Workflow Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='company-benefit-details'?' active':''?>" aria-current="page" href="{{route('company-benefit-details')}}">Company Benefit Details</a>
                </li>
            @elseif(in_array($route,$employee))            
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='employee-details'?' active':''?>" aria-current="page" href="{{route('employee-details')}}">Employee Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo Route::currentRouteName()=='employee-benefits-admin'?' active':''?>" aria-current="page" href="{{route('employee-benefits-admin')}}">Employee Benefits Details</a>
                </li>
            @endif
        </ul>
    @endif
</header>