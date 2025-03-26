@php
    $homePageAd =staticBlockImage('home-page-ad','assets/images/offer.jpg');
@endphp

<div class="container pb-16 flex justify-center">
    <div class="w-full">
        <a href="{{ route('products.index') }}" class="block">
            <div class="relative w-full h-48 sm:h-56 md:h-64 lg:h-72 xl:h-80 overflow-hidden rounded-lg shadow-md">
                <img src="{{ $homePageAd  }}" alt="ads" class="w-full h-full object-cover">
            </div>
        </a>
    </div>
</div>
