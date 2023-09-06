<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MentorCards from '@/Components/MentorCards.vue';
import { Head } from '@inertiajs/vue3';
// import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    mentors: {
        type: Array,
        required: true,
    },
    profileComplete: {
        type: Boolean,
        required: true,
    }
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">Welcome {{ $page.props.auth.user.name }}!</div>

                </div>

                <div v-if="profileComplete" class="mt-6">
                    <h1 class="text-2xl font-bold mb-4">Mentor Recommendations</h1>
                    <MentorCards :mentors="mentors"/>
                </div>

                <div v-else class="flex mt-6 justify-center items-center">
                    <div class="bg-white p-8 rounded shadow-lg">
                        <h1 class="text-2xl font-bold mb-4">Incomplete Profile</h1>
                        <p>Please Complete your Profile to see Mentors Recommendations</p>
                        <Link
                        :href="route('profile.show', $page.props.auth.user)"
                        type="button"
                        class="mt-3 rounded-md bg-indigo-600 dark:bg-indigo-500 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                        Go to Profile
                        </Link>
                    </div>
                </div>


            </div>
        </div>

    </AuthenticatedLayout>
</template>
