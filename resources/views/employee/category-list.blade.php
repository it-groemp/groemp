@php
    $category_list = Session::get("category_list");
    $current_cat = Session::get("current_cat");
@endphp
<div class="container mx-auto">
    <ul class="nav nav-tabs">
        @foreach($category_list as $category)
            <li class="nav-item">
                <a class="nav-link <?php echo $current_cat==$category->id?' active':''?>" aria-current="page" href="{{route('employee-benefits',$category->id)}}">{{$category->name}}</a>
            </li>
        @endforeach
    </ul>
</div>