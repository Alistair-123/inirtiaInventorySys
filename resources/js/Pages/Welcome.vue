<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen bg-white text-black flex flex-col">
        <!-- Top navigation -->
        <div class="flex justify-end p-4 space-x-4 mt-10 mr-20">
            <Link v-if="canLogin" href="/login" class="text-black hover:underline">Login</Link>
            <Link v-if="canRegister" href="/register" class="text-black hover:underline">Register</Link>
        </div>

        <!-- Centered card -->
        <div class="flex-1 flex items-center justify-center">
            <div class="bg-gray-100 p-8 rounded-xl shadow-xl text-center max-w-md w-full">
                <h1 class="text-2xl font-bold mb-4">Welcome to my project</h1>
            <div class="border-2  rounded-md mb-6">
                <p class="text-gray-700">group member.</p>
                <p class="text-gray-700">Alistair jan Loberternos.</p>
                <p class="text-gray-700">Jan dexter Cabilla.</p>
            </div>
                <div class="border-2 border-black rounded-md" >
                    <p class="text-gray-700">to log in as admin</p>
                    user: admin@example.com <br> pass:admin123.</div>
            </div>
        </div>
    </div>
</template>
