<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{route('employee-details')}}">
                <img src="../images/logo.png" width="80px" height="80px" alt="Logo"/>
            </a>
        </div>
        @if(Session::has("role"))
            <div class="d-flex">				
                <a class="btn btn-colored" href="{{route('admin-logout')}}" style="padding: 10px 20px; margin-right: 20px;">Logout</a>
            </div>
        @endif
    </nav>
    @if(Session::has("admin_id") && Session::get("role")=="Admin")
        <ul class="nav nav-tabs">
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
                <a class="nav-link <?php echo Route::currentRouteName()=='employee-details'?' active':''?>" aria-current="page" href="{{route('employee-details')}}">Employee Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo Route::currentRouteName()=='voucher-details'?' active':''?>" aria-current="page" href="">Voucher Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo Route::currentRouteName()=='brand-details'?' active':''?>" aria-current="page" href="{{route('brand-details')}}">Brand Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo Route::currentRouteName()=='benefit-details'?' active':''?>" aria-current="page" href="{{route('benefit-details')}}">Benefit Details</a>
            </li>
        </ul>
    @elseif(Session::has("admin_id") && Session::get("role")=="Employer")
        <ul class="nav nav-tabs">
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
                <a class="nav-link <?php echo Route::currentRouteName()=='employee-details'?' active':''?>" aria-current="page" href="{{route('employee-details')}}">Employee Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo Route::currentRouteName()=='voucher-details'?' active':''?>" aria-current="page" href="">Voucher Details</a>
            </li>
        </ul>
    @endif
</header>