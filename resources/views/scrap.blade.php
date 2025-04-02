<div x-show="open" x-transition.opacity x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div x-show="open" x-transition.scale.origin.center class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 p-6 h-[70vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-lg font-semibold">Add New Build</h3>
            <button @click="open = false" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <form @submit.prevent="submitForm" class="h-full flex flex-col">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Build Name</label>
                <input x-model="formData.name" type="text" required class="w-full px-3 py-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea x-model="formData.description" rows="3" class="w-full px-3 py-2 border rounded-md"></textarea>
            </div>

            <!-- Parts Section - Increased Height -->
            <div class="mb-4 flex-grow overflow-y-auto border rounded-md p-3" style="min-height: 400px;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Parts</label>
                    <template x-for="part in formData.parts" :key="part.id">
                        <div class="flex space-x-2 items-center mb-2">
                            <select x-model="part.type" class="border p-2 rounded-md">
                                <option value="">Select Type</option>
                                <template x-for="type in partTypes">
                                    <option :value="type" x-text="type"></option>
                                </template>
                            </select>
                            <input x-model="part.name" type="text" placeholder="Name" class="border p-2 rounded-md">
                            <input x-model="part.price" type="number" placeholder="Price" class="border p-2 rounded-md" @input="updateTotalPrice">
                            <input x-model="part.quantity" type="number" placeholder="Qty" class="border p-2 rounded-md min-w-[60px]" min="1">
                            <input type="file" @change="part.picture = $event.target.files[0]" class="border p-2 rounded-md">
                            <button type="button" @click="removePart(part.id)" class="text-red-500 hover:text-red-700">✕</button>
                        </div>
                    </template>                                             
                
                <!-- Move the Add Button Inside the Container -->
                <button type="button" @click="addPart" class="mt-3 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md w-full">
                    + Add Part
                </button>
            </div>  

            <!-- Total Price -->
            <div class="text-right font-bold text-lg mb-4">Total Price: $<span x-text="totalPrice"></span></div>

            <div class="flex justify-end space-x-3 border-t pt-4">
                <button type="button" @click="open = false" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md">Save Build</button>
            </div>
        </form>
    </div>
</div>