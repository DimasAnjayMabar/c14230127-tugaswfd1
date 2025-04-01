<!-- Detail Modal -->
<div x-show="Alpine.store('detailModal').open" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg w-2/3 h-[60vh] p-6 relative overflow-hidden flex flex-col">
        <!-- Close Button -->
        <button @click="Alpine.store('detailModal').open = false" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">X</button>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Detail Build</h2>

        <!-- Scrollable Content (Fixed Height) -->
        <div class="flex-1 overflow-y-auto px-2 space-y-4" x-show="Alpine.store('detailModal').build">
            <template x-for="part in Alpine.store('detailModal').build.parts" :key="part.id">
                <div class="flex items-center space-x-4 bg-gray-100 p-4 rounded-lg shadow">
                    <img :src="part.image ?? '/placeholder.png'" class="w-16 h-16 rounded-lg object-cover">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800" x-text="part.name"></h3>
                        <p class="text-gray-600 text-sm">Type: <span x-text="part.type"></span></p>
                        <p class="text-blue-700 font-semibold">$<span x-text="part.price"></span></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Action Buttons (Fixed Position) -->
        <div class="mt-4 text-center">
            <button @click="Alpine.store('detailModal').open = false" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Close</button>
        </div>
    </div>
</div>
