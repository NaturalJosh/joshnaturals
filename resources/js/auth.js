import { reactive, computed } from 'vue';

const saved = localStorage.getItem('auth');

export const state = reactive(saved ? JSON.parse(saved) : { token: null, user: null });

export const isLoggedIn = computed(() => !!state.token);
export const isAdmin = computed(() => state.user?.role === 'admin');

export function setAuth(token, user) {
    state.token = token;
    state.user = user;
    localStorage.setItem('auth', JSON.stringify({ token, user }));
}

export function clearAuth() {
    state.token = null;
    state.user = null;
    localStorage.removeItem('auth');
}