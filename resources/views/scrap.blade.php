<!-- Modal Delete -->
<div x-data="{
    open: false,
    buildId: 0,
    fetchForDelete(id) {
        this.buildId = id;
        this.open = true;
    },
    confirmDelete() {
        fetch(`/builds/${this.buildId}/delete`, {
            method: 'POST', // Laravel defaultnya hanya menerima POST, jadi kita override dengan _method
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ _method: 'DELETE' }) // Laravel butuh _method untuk DELETE
        })
        .then(response => response.json())
        .then(() => {
            this.open = false;
            window.location.reload(); // Refresh halaman setelah penghapusan
        })
        .catch(error => console.error('Error deleting build:', error));
    }
}" x-show="open" 
    x-transition.opacity.duration.300ms
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
    <div x-show="open" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0 scale-90" 
        x-transition:enter-end="opacity-100 scale-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100 scale-100" 
        x-transition:leave-end="opacity-0 scale-90"
        class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold">Confirm Deletion</h2>
        <p class="text-gray-600 mt-2">Are you sure you want to delete this build <span x-text="buildId"></span> ? </p>
        <div class="flex justify-end mt-4 space-x-2">
            <button @click="open = false" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
            <button @click="confirmDelete()" class="px-4 py-2 bg-red-500 text-white rounded-lg">Delete</button>
        </div>
    </div>
</div>

<!-- Script to trigger modal opening -->
<script>
    function fetchForDelete(buildId) {
        Alpine.store('deleteModal').buildId = buildId;
        Alpine.store('deleteModal').open = true;
    }
</script>
