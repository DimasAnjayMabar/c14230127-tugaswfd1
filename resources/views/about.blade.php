@extends('navbar')

@section('section')
    <div class="min-h-screen bg-blue-100 flex items-center justify-center p-6">
        <div class="bg-white border border-gray-200 rounded-lg shadow-md md:flex md:max-w-3xl dark:border-gray-700 dark:bg-gray-800 overflow-hidden">
            
            <!-- Picture -->
            <img class="object-cover w-full h-30 md:h-auto md:w-1/3" src="{{ asset('storage/fotoWebsite/pc.jpg') }}" alt="PC Image">
            
            <!-- Content -->
            <div class="p-6 flex flex-col justify-center md:w-2/3">
                <h5 class="text-2xl font-bold text-gray-900 dark:text-white">Strong PC, Strong Activity</h5>
                <p class="text-gray-700 dark:text-gray-400 mt-2">We are serving people that have imagination and desires. All of the requests are our pleasure to serve.</p>
                <p class="text-gray-700 dark:text-gray-400 mt-2">Contact us here:</p>
                
                <!-- Button -->
                <div class="mt-4 flex flex-wrap gap-3">
                    <a class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Whatsapp
                    </a>
                    <a class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Email
                    </a>
                    <a class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
