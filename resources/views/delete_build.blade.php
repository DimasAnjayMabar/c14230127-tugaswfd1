<!-- Modal Delete -->
<div x-data="{
    async confirmDelete() {
        try {
            const response = await fetch(`/builds/${Alpine.store('deleteModal').build.id}/delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            if (response.ok) {
                // Close modal and refresh page or update UI as needed
                Alpine.store('deleteModal').open = false;
                window.location.reload();
            } else {
                console.error('Failed to delete build');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
}" 
x-show="$store.deleteModal.open" 
x-transition.opacity.duration.300ms
class="fixed inset-0 flex items-center justify-center" x-cloak>
    <div x-show="$store.deleteModal.open" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0 scale-90" 
        x-transition:enter-end="opacity-100 scale-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100 scale-100" 
        x-transition:leave-end="opacity-0 scale-90"
        class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold">Confirm Deletion</h2>
        <p class="text-gray-600 mt-2">Are you sure you want to delete the build "<span x-text="$store.deleteModal.build.name"></span>"?</p>
        <div class="flex justify-end mt-4 space-x-2">
            <button @click="$store.deleteModal.open = false" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
            <button @click="confirmDelete()" class="px-4 py-2 bg-red-500 text-white rounded-lg">Delete</button>
        </div>
    </div>
</div>