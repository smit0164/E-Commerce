@php
    $homePageBanner = staticBlockImage('home-page-banner','assets/images/banner-bg.jpg');
@endphp

<div class="bg-cover bg-no-repeat bg-center py-36" style="background-image: url({{$homePageBanner}});">
    <div class="container">
        <h1 class="text-6xl text-gray-800 font-medium mb-4 capitalize">
            Discover the Best <br>  
            Deals & Collections
        </h1>
        <p>Shop top-quality products across fashion, electronics, home, and more.</p>
        <div class="mt-12">
            <a href="{{ route('products.index') }}" class="bg-primary border border-primary text-white px-8 py-3 font-medium 
                rounded-md hover:bg-transparent hover:text-primary">Shop Now</a>
        </div>
    </div>
</div>