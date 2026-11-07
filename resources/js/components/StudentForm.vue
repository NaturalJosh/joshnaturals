<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
    student: { type: Object, default: null },
    errors: { type: Object, default: () => ({}) },
    saving: { type: Boolean, default: false },
});
const emit = defineEmits(['save', 'cancel']);

const fields = [
    { key: 'firstname', label: 'First name', type: 'text' },
    { key: 'lastname', label: 'Last name', type: 'text' },
    { key: 'course', label: 'Course', type: 'text' },
    { key: 'year', label: 'Year', type: 'number' },
];

const blank = () => ({ firstname: '', lastname: '', course: '', year: '' });
const form = reactive(blank());

watch(
    () => props.student,
    (s) => {
        Object.assign(form, s
            ? { firstname: s.firstname, lastname: s.lastname, course: s.course, year: s.year }
            : blank());
    },
    { immediate: true },
);

function submit() {
    emit('save', { ...form });
}
</script>

<template>
    <form class="mb-6 rounded border bg-white p-4" @submit.prevent="submit">
        <h2 class="mb-3 text-lg font-semibold">{{ student ? 'Edit student' : 'Add student' }}</h2>

        <div class="grid gap-3 sm:grid-cols-2">
            <div v-for="f in fields" :key="f.key">
                <label class="mb-1 block text-sm font-medium">{{ f.label }}</label>
                <input
                    v-model="form[f.key]"
                    :type="f.type"
                    class="w-full rounded border p-2"
                    :class="{ 'border-red-500': errors[f.key] }"
                />
                <p v-if="errors[f.key]" class="mt-1 text-sm text-red-600">{{ errors[f.key][0] }}</p>
            </div>
        </div>

        <div class="mt-4 space-x-2">
            <button type="submit" :disabled="saving" class="rounded bg-green-600 px-4 py-2 text-white disabled:opacity-50">
                {{ saving ? 'Saving...' : student ? 'Update' : 'Add' }}
            </button>
            <button v-if="student" type="button" class="rounded bg-gray-300 px-4 py-2" @click="emit('cancel')">Cancel</button>
        </div>
    </form>
</template>