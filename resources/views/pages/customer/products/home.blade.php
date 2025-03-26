@extends('layouts.users.app')


@section('content')

<!-- banner -->
<x-users.homepage-banner/>
<!-- ./banner -->


 <!-- features -->
<x-users.homepage-features/>
<!-- ./features -->

 
<div class="container py-12 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 uppercase mb-10 text-center tracking-tight">Shop by Category</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($categories as $category)
            <x-users.shop-by-category :category="$category" />
        @endforeach
    </div>
</div>
   
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
<x-users.homepage-ad/>
  
   
        
</div>

@endsection
