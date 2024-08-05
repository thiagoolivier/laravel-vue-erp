import router from '@/router';
import axios, { type AxiosInstance } from 'axios';

// Configuração global do Axios
const api: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('access_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (e) => {
    return Promise.reject(e);
  }
);

api.interceptors.response.use(
  (res) => {
    return res;
  },
  (e) => {
    if (e.response && e.response.status === 401) {
      localStorage.removeItem('access_token');
      router.push({ name: 'login' });
    }
    return Promise.reject(e);
  }
);

export default api;
