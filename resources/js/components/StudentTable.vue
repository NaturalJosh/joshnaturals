<script setup>
defineProps({
    students: { type: Array, required: true },
    canManage: { type: Boolean, default: true },
});
defineEmits(['edit', 'delete']);
</script>

<template>
    <div class="overflow-x-auto rounded border bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">First name</th>
                    <th class="p-3">Last name</th>
                    <th class="p-3">Course</th>
                    <th class="p-3">Year</th>
                    <th v-if="canManage" class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="students.length === 0">
                    <td colspan="6" class="p-4 text-center text-gray-500">No students found.</td>
                </tr>
                <tr v-for="s in students" :key="s.id" class="border-t">
                    <td class="p-3">{{ s.id }}</td>
                    <td class="p-3">{{ s.firstname }}</td>
                    <td class="p-3">{{ s.lastname }}</td>
                    <td class="p-3">{{ s.course }}</td>
                    <td class="p-3">{{ s.year }}</td>
                    <td v-if="canManage" class="p-3 space-x-2">
                        <button class="rounded bg-blue-600 px-3 py-1 text-white" @click="$emit('edit', s)">Edit</button>
                        <button class="rounded bg-red-600 px-3 py-1 text-white" @click="$emit('delete', s)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>