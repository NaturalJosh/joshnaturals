const BASE = '/api';

async function request(path, options = {}) {
    const res = await fetch(BASE + path, {
        ...options,
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...(options.headers || {}),
        },
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok) {
        const error = new Error(data.message || 'Request failed');
        error.status = res.status;
        error.errors = data.errors || {};
        throw error;
    }
    return data;
}

export const studentApi = {
    all: () => request('/students'),
    create: (payload) => request('/students', { method: 'POST', body: JSON.stringify(payload) }),
    update: (id, payload) => request(`/students/${id}`, { method: 'PUT', body: JSON.stringify(payload) }),
    remove: (id) => request(`/students/${id}`, { method: 'DELETE' }),
};