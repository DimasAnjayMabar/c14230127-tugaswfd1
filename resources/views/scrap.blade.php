@extends('navbar')

@section('section')
<div class="min-h-screen bg-blue-100 pb-16" x-data="buildData()">
    <!-- ... (keep your existing header and button code) ... -->
    
    <!-- Builds Card Grid -->
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($builds as $build)
            <div class="bg-white rounded-lg shadow-xl overflow-hidden transition-transform duration-300 hover:scale-105">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-blue-900 mb-2">{{ $build->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ $build->description ?? 'No description' }}</p>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($build->parts as $part)
                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                            {{ $part->type }} (x{{ $part->pivot->quantity }})
                        </span>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-blue-700">${{ number_format($build->total_price, 2) }}</span>
                        <div class="flex space-x-2">
                            <button @click="openDetailModal({{ $build->id }})" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                Details
                            </button>
                            <!-- Edit and Delete buttons -->
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Detail Build Modal -->
    <div x-show="isDetailModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto z-50 flex items-center justify-center">
        <div @click.away="closeDetailModal"
             x-show="isDetailModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-2xl mx-4">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-bold text-blue-900" x-text="currentBuild.name"></h3>
                <button @click="closeDetailModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4">
                <p class="text-gray-600 mb-6" x-text="currentBuild.description || 'No description available'"></p>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-blue-50 p-3 rounded-lg">
                        <span class="font-medium text-blue-800">Total Price:</span>
                        <span class="text-lg font-bold text-blue-700" 
                              x-text="'$' + currentBuild.total_price.toFixed(2)"></span>
                    </div>
                    
                    <h4 class="text-lg font-semibold text-blue-900 mt-6 mb-3">Components</h4>
                    
                    <template x-for="part in currentBuild.parts" :key="part.id">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <div class="flex-shrink-0 mr-4">
                                <img :src="part.picture" :alt="part.name" 
                                     class="h-12 w-12 object-contain bg-white p-1 rounded border">
                            </div>
                            <div class="flex-grow">
                                <div class="font-medium text-gray-900" x-text="part.name"></div>
                                <div class="text-sm text-gray-500">
                                    <span class="capitalize" x-text="part.type"></span> • 
                                    Qty: <span x-text="part.quantity"></span> • 
                                    $<span x-text="part.price.toFixed(2)"></span> each
                                </div>
                            </div>
                            <div class="ml-4 font-medium">
                                $<span x-text="(part.price * part.quantity).toFixed(2)"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end p-4 border-t">
                <button @click="closeDetailModal" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    function buildData() {
        return {
            isDetailModalOpen: false,
            currentBuild: {
                name: '',
                description: '',
                total_price: 0,
                parts: []
            },
            openDetailModal(buildId) {
                fetch(`/builds/${buildId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        this.currentBuild = {
                            name: data.build.name,
                            description: data.build.description,
                            total_price: data.total_price,
                            parts: data.parts.map(part => ({
                                ...part,
                                picture: part.picture || '/images/default-part.png'
                            }))
                        };
                        this.isDetailModalOpen = true;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to load build details');
                    });
            },
            closeDetailModal() {
                this.isDetailModalOpen = false;
            }
        }
    }
</script>
@endsection