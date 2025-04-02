@extends('navbar')

@section('section')
    <div class="min-h-screen bg-blue-100 pb-16" x-data>
        <!-- Title -->
        <div class="text-center pt-16">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-black md:text-5xl lg:text-6xl">
                <span class="block text-blue-800 mb-3 text-sm uppercase tracking-widest">Build Your PC Here</span>
                <span class="block text-blue-900 text-5xl md:text-6xl lg:text-7xl font-bold mt-4">Custom PC Builder</span>
            </h1>
            <div class="w-24 h-1 mx-auto bg-blue-600 my-6 rounded-full"></div>
        </div>
        
        <!-- Builds Card Grid -->
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-100">
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
                                <button @click="fetchBuildDetail({{ $build->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Details
                                </button>
                                <button @click="fetchForEdit({{ $build->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Edit
                                </button>
                                <button @click="fetchForDelete({{ $build->id }})" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        @include('detail_build')
        @include('edit_build')
        @include('delete_build')

        @include('add_build')

    
    <!-- Script to load detail build modal -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('detailModal', { open: false, build: null });
        });

        function fetchBuildDetail(buildId) {
            fetch(`/builds/${buildId}/detail`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data received:", data);
                    
                    // Pastikan store sudah ada sebelum mengubah datanya
                    if (!Alpine.store('detailModal')) {
                        console.error("Alpine store 'detailModal' not found!");
                        return;
                    }

                    Alpine.store('detailModal').open = true;
                    Alpine.store('detailModal').build = data;
                })
                .catch(error => console.error('Error fetching details:', error));
        }
    </script>

    <!-- Script to load edit build modal -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('editModal', {
                open: false,
                build: {
                    id: null,
                    name: '',
                    description: '',
                    parts: []
                }
            });
        });
    
        function fetchForEdit(buildId) {
            fetch(`/builds/${buildId}/detail`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data received:", data);

                    Alpine.store('editModal').build = data;

                    // Paksa Alpine membaca perubahan state
                    setTimeout(() => {
                        Alpine.store('editModal').open = true;
                        console.log("Forced modal open:", Alpine.store('editModal').open);
                    }, 100);
                })
                .catch(error => console.error('Error fetching details:', error));
        }
    </script>

    <!-- Script to load delete build modal -->
    <script>
         document.addEventListener('alpine:init', () => {
            Alpine.store('deleteModal', {
                open: false,
                build: {
                    id: null,
                    name: '',
                    description: '',
                }
            });
        });

        function fetchForDelete(buildId) {
            fetch(`/builds/${buildId}/detail`)
            .then(response => response.json())
            .then(data => {
                Alpine.store('deleteModal').open = true;
                Alpine.store('deleteModal').build = data;
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection