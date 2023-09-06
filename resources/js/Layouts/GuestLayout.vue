<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>

            <Link href="/">
                <svg
  xmlns="http://www.w3.org/2000/svg"
  width="100"
  height="100"
  viewBox="0 0 100 100"
>
  <!-- Head -->
  <circle cx="50" cy="40" r="35" fill="#ffcc66" />

  <!-- Eyes -->
  <circle cx="40" cy="35" r="4" fill="#333" />
  <circle cx="60" cy="35" r="4" fill="#333" />

  <!-- Mouth -->
  <path
    d="M40 50 Q50 60, 60 50"
    fill="none"
    stroke="#333"
    stroke-width="2"
  />

  <!-- Body -->
  <rect x="35" y="55" width="30" height="30" fill="#0099cc" />
</svg>
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
