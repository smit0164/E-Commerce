@extends('layouts.users.app')


@section('content')

<!-- banner -->
<x-users.homepage-banner/>
<!-- ./banner -->


 <!-- features -->
<x-users.homepage-features/>
<!-- ./features -->

 
   
<x-users.shop-by-category :categories="$categories"/>
   
<!-- ./categories -->

 <!-- new arrival -->
 <div class="container py-16">
    <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">Top New Arrivals</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <x-users.product-card :product="$product" />
        @endforeach
    </div>
</div>
<!-- ./new arrival -->

 <!-- ads -->
 <div class="container pb-16">
    <a href="#">
        <img src="{{ asset('assets/images/offer.jpg') }}" alt="ads" class="w-full">

    </a>
</div>
<!-- ./ads -->

{{-- <div class="container pb-16">
    <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">Recommended for You</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach ($recommendedProducts as $product)
            <x-users.product-card :product="$product" />
        @endforeach
    </div>
</div> --}}
  
   
        
</div>

@endsection
