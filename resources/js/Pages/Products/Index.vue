<script setup>
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps({
  products: Object,
  isAdmin: {
    type: Boolean,
    default: false
  },
  errors: Object,
})

function goTo(url) {
  router.visit(url)
}

function deleteProduct(id) {
  if (confirm('Are you sure you want to delete this product?')) {
    router.delete(route('products.destroy', id))
  }
}

function edit(product) {
  const name = prompt('Edit name:', product.name)
  const price = prompt('Edit price:', product.price)
  const description = prompt('Edit description:', product.description)

  if (name && price) {
    router.put(route('products.update', product.id), {
      name,
      price,
      description,
      image: null
    })
  }
}
</script>

<template>
  <Head title="Product List" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Product List</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div
                v-for="product in products.data"
                :key="product.id"
                class="border rounded-lg shadow-sm p-5 bg-white hover:shadow-md transition"
              >
                <img
                  v-if="product.image"
                  :src="'/storage/' + product.image"
                  alt="Product Image"
                  class="w-full h-40 object-cover rounded mb-4"
                />
                <h2 class="text-lg font-semibold text-gray-900">{{ product.name }}</h2>
                <p class="text-blue-600 font-medium">₱{{ product.price }}</p>
                <p class="text-gray-700 text-sm mt-2">{{ product.description }}</p>

                <div v-if="isAdmin" class="mt-4 space-x-2">
                  <button
                    @click="edit(product)"
                    class="inline-block px-4 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteProduct(product.id)"
                    class="inline-block px-4 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="products.data.length > 0" class="mt-8 flex justify-center items-center space-x-4 p-8">
              <button
                :disabled="!products.prev_page_url"
                @click="goTo(products.prev_page_url)"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded disabled:opacity-50"
              >
                Prev
              </button>

              <button
                :disabled="!products.next_page_url"
                @click="goTo(products.next_page_url)"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded disabled:opacity-50"
              >
                Next
              </button>
            </div>

            <div v-else class="mt-8 text-center text-gray-500">
              No products found.
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
