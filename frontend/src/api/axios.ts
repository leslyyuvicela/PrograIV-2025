// api/axios.ts
import axios, { AxiosRequestConfig, AxiosError, InternalAxiosRequestConfig } from 'axios';
import { useAuthStore } from '@/stores/authStore';
import { refreshToken } from '@/services/authService';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: true,
  timeout: 10000,
});

// ✅ INTERCEPTOR DE REQUEST
api.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    const authStore = useAuthStore();
    const token = authStore.accessToken;

    if (token && config.headers) {
      config.headers['Authorization'] = `Bearer ${token}`;
    }

    return config;
  },
  (error: AxiosError) => Promise.reject(error)
);

// ✅ INTERCEPTOR DE RESPUESTA
/*
api.interceptors.response.use(
  response => response,
  async (error: AxiosError) => {
    const originalRequest = error.config as AxiosRequestConfig & { _retry?: boolean };

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true;
      const authStore = useAuthStore();

      try {
        const accesToken  = await refreshToken(); // Corregido: era "accessToken" antes, debe coincidir con el objeto retornado
        originalRequest.headers = {
          ...originalRequest.headers,
          Authorization: `Bearer ${accesToken}`, // ⚠️ Usar token actualizado directamente en la solicitud reintentada
        };
        return api(originalRequest);
      } catch (err) {
        //authStore.logOut();
        return Promise.reject(err);
      }
    }

    return Promise.reject(error);
  }
);
*/

export default api;
