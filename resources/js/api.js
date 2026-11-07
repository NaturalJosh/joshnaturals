import { state, clearAuth } from './auth';

const BASE = '/api';

async function request(path, options = {}) {
    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(options.headers || {}),
    };

    if (state.token) {
        headers.Authorization = `Bearer ${state.token}`;
    }

    const res = await fetch(BASE + path, { ...options, headers });
    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
        if (res.status === 401 && state.token) clearAuth(); // token expired or revoked

        const error = new Error(data.message || 'Request failed');
        error.status = res.status;
        error.errors = data.errors || {};
        throw error;
    }
    return data;
}

export const authApi = {
    login: (credentials) => request('/login', { method: 'POST', body: JSON.stringify(credentials) }),
    logout: () => request('/logout', { method: 'POST' }),
};

export const studentApi = {
    all: () => request('/students'),
    create: (payload) => request('/students', { method: 'POST', body: JSON.stringify(payload) }),
    update: (id, payload) => request(`/students/${id}`, { method: 'PUT', body: JSON.stringify(payload) }),
    remove: (id) => request(`/students/${id}`, { method: 'DELETE' }),
};