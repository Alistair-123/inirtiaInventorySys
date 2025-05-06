<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
  errors: Object,
})

const form = useForm({
  name: '',
  price: '',
  description: '',
  image: null
})

const handleFile = (e) => {
  form.image = e.target.files[0]
}

const submit = () => {
  form.post(route('products.store'), {
    forceFormData: true
  })
}
</script>

<template>
  <Head title="Create Product" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Product</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="submit" class="space-y-4">
              <div>
                <input
                  v-model="form.name"
                  type="text"
                  placeholder="Product Name"
                  class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <div v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</div>
              </div>

              <div>
                <input
                  v-model="form.price"
                  type="number"
                  placeholder="Price"
                  class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <div v-if="errors.price" class="text-red-500 text-sm mt-1">{{ errors.price }}</div>
              </div>

              <div>
                <textarea
                  v-model="form.description"
                  placeholder="Description"
                  rows="4"
                  class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
                <div v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</div>
              </div>

              <div>
                <input
                  @change="handleFile"
                  type="file"
                  class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100"
                />
                <div v-if="errors.image" class="text-red-500 text-sm mt-1">{{ errors.image }}</div>
              </div>

              <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Submitting...' : 'Submit' }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
