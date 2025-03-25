@extends('layouts.users.app')

@section('content')
<div class="container py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Contact Form -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Get in Touch</h2>
            <p class="text-gray-600 mb-6">Fill out the form below and we'll get back to you as soon as possible.</p>

            <form action="" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="text-gray-700 font-medium">Full Name</label>
                    <input type="text" id="name" name="name" class="input-box mt-1" placeholder="Your Name" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="text-gray-700 font-medium">Email Address</label>
                    <input type="email" id="email" name="email" class="input-box mt-1" placeholder="you@example.com" required>
                </div>

                <div class="mb-4">
                    <label for="subject" class="text-gray-700 font-medium">Subject</label>
                    <input type="text" id="subject" name="subject" class="input-box mt-1" placeholder="Subject" required>
                </div>

                <div class="mb-4">
                    <label for="message" class="text-gray-700 font-medium">Message</label>
                    <textarea id="message" name="message" class="input-box mt-1 h-32" placeholder="Write your message here..." required></textarea>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-2 rounded-md hover:bg-primary-dark transition">
                    Send Message
                </button>
            </form>
        </div>

        <!-- Contact Info -->
        <div>
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Contact Information</h2>
                <p class="text-gray-600 mb-4">You can also reach us via the details below.</p>

                <div class="flex items-center mb-4">
                    <i class="fas fa-map-marker-alt text-primary text-3xl"></i>
                    <p class="ml-4 text-gray-700 text-base font-medium">Model Heights, Iskcon, Ahmedabad, India</p>
                </div>

                <div class="flex items-center mb-4">
                    <i class="fas fa-phone-alt fa-flip-horizontal text-primary text-2xl"></i>
                    <p class="ml-4 text-gray-700 text-base font-medium">
                        <a href="tel:+919265601854" class="hover:text-primary transition">+91 9265601854</a>
                    </p>
                </div>

                <div class="flex items-center mb-4">
                    <i class="fas fa-envelope text-primary text-2xl"></i>
                    <p class="ml-4 text-gray-700 text-base font-medium">
                        <a href="mailto:smitpatel.ast@gmail.com" class="hover:text-primary transition">smitpatel.ast@gmail.com</a>
                    </p>
                </div>

                <!-- Google Map (India, Ahmedabad) -->
                <div class="mt-6">
                    <iframe 
                        class="w-full h-64 rounded-lg"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.176108477021!2d72.50344267518993!3d23.017740579171097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b2fdbfd8f9d%3A0x8503cf4dbb6d4763!2sModel%20Heights%2C%20Iskcon%2C%20Ahmedabad%2C%20Gujarat%2C%20India!5e0!3m2!1sen!2sin!4v1710150484590!5m2!1sen!2sin"
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

