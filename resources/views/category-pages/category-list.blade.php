<div class="container mx-auto">
    <ul class="nav nav-tabs">
        @foreach($category_list as $category)
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="">{{$category->name}}</a>
            </li>
        @endforeach
    </ul>
</div>