<script setup>
import { ref, reactive, onMounted } from 'vue';
import { studentApi } from './api';
import StudentForm from './components/StudentForm.vue';
import StudentTable from './components/StudentTable.vue';

const students = ref([]);
const editing = ref(null);
const loading = ref(false);
const saving = ref(false);
const errors = ref({});
const formKey = ref(0);
const message = reactive({ type: '', text: '' });

function notify(type, text) {
    message.type = type;
    message.text = text;
    setTimeout(() => (message.text = ''), 4000);
}

async function loadStudents() {
    loading.value = true;
    try {
        const res = await studentApi.all();
        students.value = res.data;
    } catch (e) {
        notify('error', e.message);
    } finally {
        loading.value = false;
    }
}

async function saveStudent(payload) {
    saving.value = true;
    errors.value = {};
    try {
        if (editing.value) {
            const res = await studentApi.update(editing.value.id, payload);
            const i = students.value.findIndex((s) => s.id === res.data.id);
            students.value[i] = res.data;
            notify('success', res.message);
        } else {
            const res = await studentApi.create(payload);
            students.value.push(res.data);
            notify('success', res.message);
        }
        editing.value = null;
        formKey.value++; // resets the form
    } catch (e) {
        if (e.status === 422) {
            errors.value = e.errors;
            notify('error', 'Please fix the validation errors.');
        } else {
            notify('error', e.message);
        }
    } finally {
        saving.value = false;
    }
}

async function removeStudent(student) {
    if (!confirm(`Delete ${student.firstname} ${student.lastname}?`)) return;
    try {
        const res = await studentApi.remove(student.id);
        students.value = students.value.filter((s) => s.id !== student.id);
        notify('success', res.message);
    } catch (e) {
        notify('error', e.message);
    }
}

onMounted(loadStudents);
</script>

<template>
    <div class="mx-auto max-w-4xl p-6">
        <h1 class="mb-6 text-2xl font-bold">Student Management</h1>

        <p
            v-if="message.text"
            class="mb-4 rounded p-3"
            :class="message.type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
        >
            {{ message.text }}
        </p>

        <StudentForm
            :key="formKey"
            :student="editing"
            :errors="errors"
            :saving="saving"
            @save="saveStudent"
            @cancel="editing = null"
        />

        <p v-if="loading" class="text-gray-500">Loading students...</p>
        <StudentTable v-else :students="students" @edit="editing = $event" @delete="removeStudent" />
    </div>
</template>