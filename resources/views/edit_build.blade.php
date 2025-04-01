<div x-data="{
    open: false,
    formData: {
        id: null,
        name: '',
        description: '',
        parts: []
    },
    totalPrice: 0,
    partTypes: ['CPU', 'GPU', 'RAM', 'Storage', 'Motherboard', 'Power Supply', 'Cooling', 'Case'],
    addPart() {
        this.formData.parts.push({ type: '', name: '', price: 0, image: null });
        this.updateTotalPrice();
    },
    removePart(index) {
        this.formData.parts.splice(index, 1);
        this.updateTotalPrice();
    },
    updateTotalPrice() {
        this.totalPrice = this.formData.parts.reduce((sum, part) => sum + parseFloat(part.price || 0), 0);
    },
    async submitForm() {
        try {
            const formData = new FormData();
            formData.append('name', this.formData.name);
            formData.append('description', this.formData.description);
            formData.append('total_price', this.totalPrice);

            this.formData.parts.forEach((part, index) => {
                formData.append(`parts[${index}][type]`, part.type);
                formData.append(`parts[${index}][name]`, part.name);
                formData.append(`parts[${index}][price]`, part.price);
                if (part.image) {
                    formData.append(`parts[${index}][image]`, part.image);
                }
            });

            const url = this.formData.id ? `/builds/${this.formData.id}/update` : '/builds/add';
            const method = this.formData.id ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });

            if (response.ok) {
                window.location.reload();
            } else {
                const errors = await response.json();
                console.error('Error:', errors);
            }
        } catch (error) {
            console.error('Network error:', error);
        }
    }
}"
x-init="$watch('$store.editModal.build', (data) => {
    if (data) {
        open = true;
        formData.id = data.id;
        formData.name = data.name;
        formData.description = data.description;
        formData.parts = data.parts.map(part => ({
            type: part.type,
            name: part.name,
            price: part.price,
            image: null // Reset image selection for edit
        }));
        updateTotalPrice();
    }
})">
    <!-- Modal -->
    <div x-data="$store.editModal">
        <div x-bind:class="$store.editModal.open ? 'block' : 'hidden'" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 p-6 h-[60vh] overflow-y-auto">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-semibold">Edit Build</h3>
                    <button @click="$store.editModal.open = false" class="text-gray-500 hover:text-gray-700">✕</button>
                </div>

                <form @submit.prevent="submitForm" class="h-full flex flex-col">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Build Name</label>
                        <input x-model="$store.editModal.build.name" type="text" required class="w-full px-3 py-2 border rounded-md">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea x-model="$store.editModal.build.description" rows="3" class="w-full px-3 py-2 border rounded-md"></textarea>
                    </div>

                    <!-- Parts Section -->
                    <div class="mb-4 flex-grow overflow-y-auto border rounded-md p-3" style="min-height: 400px;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Parts</label>
                        <template x-for="(part, index) in $store.editModal.build.parts" :key="index">
                            <div class="flex space-x-2 items-center mb-2">
                                <select x-model="part.type" class="border p-2 rounded-md">
                                    <option value="">Select Type</option>
                                    <template x-for="type in ['CPU', 'GPU', 'RAM', 'Storage', 'Motherboard', 'Power Supply', 'Cooling', 'Case']">
                                        <option :value="type" x-text="type"></option>
                                    </template>
                                </select>
                                <input x-model="part.name" type="text" placeholder="Name" class="border p-2 rounded-md">
                                <input x-model="part.price" type="number" placeholder="Price" class="border p-2 rounded-md">
                                <button type="button" @click="$store.editModal.build.parts.splice(index, 1)" class="text-red-500 hover:text-red-700">✕</button>
                            </div>
                        </template>

                        <button type="button" @click="addPart" class="mt-3 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md w-full">
                            + Add Part
                        </button>
                    </div>

                    <!-- Total Price -->
                    <div class="text-right font-bold text-lg mb-4">
                        Total Price: $<span x-text="$store.editModal.build.parts.reduce((sum, part) => sum + parseFloat(part.price || 0), 0)"></span>
                    </div>

                    <div class="flex justify-end space-x-3 border-t pt-4">
                        <button type="button" @click="$store.editModal.open = false" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md">Save Build</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
