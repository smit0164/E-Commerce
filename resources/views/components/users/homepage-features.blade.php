<div class="container py-16">
    <div class="w-10/12 grid grid-cols-1 md:grid-cols-3 gap-6 mx-auto text-center">
        @php
            $features = [
                ['icon' => 'delivery-van.svg','title' => 'Exclusive Deals', 'text' => 'Get access to special discounts & offers.'],
                ['icon' => 'money-back.svg', 'title' =>  'High-Quality Products', 'text' => 'We ensure top-notch quality in every product.'],
                ['icon' => 'service-hours.svg','title' => '24/7 Customer Support',  'text' => 'We’re here to help anytime, anywhere.']
            ];
        @endphp
        @foreach ($features as $feature)
            <div class="border border-primary rounded-sm px-3 py-6 flex justify-center items-center gap-5">
                <img src="{{ asset('assets/images/icons/' . $feature['icon']) }}" alt="{{ $feature['title'] }}" class="w-12 h-12 object-contain">
                <div>
                    <h4 class="font-medium capitalize text-lg">{{ $feature['title'] }}</h4>
                    <p class="text-gray-500 text-sm">{{ $feature['text'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>