<script setup>
import { reactive, ref } from 'vue';
import { authApi } from '../api';
import { setAuth } from '../auth';

const emit = defineEmits(['logged-in']);

const form = reactive({ email: '', password: '' });
const loading = ref(false);
const error = ref('');
const errors = ref({});

async function submit() {
    loading.value = true;
    error.value = '';
    errors.value = {};
    try {
        const res = await authApi.login({ ...form });
        setAuth(res.access_token, res.user);
        emit('logged-in');
    } catch (e) {
        if (e.status === 422) errors.value = e.errors;
        else error.value = e.message;
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <form class="mx-auto max-w-sm rounded border bg-white p-6" @submit.prevent="submit">
        <h2 class="mb-4 text-xl font-semibold">Login</h2>

        <p v-if="error" class="mb-3 rounded bg-red-100 p-2 text-sm text-red-800">{{ error }}</p>

        <label class="mb-1 block text-sm font-medium">Email</label>
        <input v-model="form.email" type="email" class="mb-1 w-full rounded border p-2" />
        <p v-if="errors.email" class="mb-2 text-sm text-red-600">{{ errors.email[0] }}</p>

        <label class="mb-1 mt-2 block text-sm font-medium">Password</label>
        <input v-model="form.password" type="password" class="mb-1 w-full rounded border p-2" />
        <p v-if="errors.password" class="mb-2 text-sm text-red-600">{{ errors.password[0] }}</p>

        <button type="submit" :disabled="loading" class="mt-4 w-full rounded bg-blue-600 py-2 text-white disabled:opacity-50">
            {{ loading ? 'Logging in...' : 'Login' }}
        </button>
    </form>
</template>