//authService.ts
import api from '@/api/axios';
import { useAuthStore } from '@/stores/authStore';
import { LoginResponse, RegisterResponse, RegisterFormData } from '@/types/authTypes';

const API_URL = '/api/auth/';


export const register = async (formData: RegisterFormData): Promise<RegisterResponse> => {
    //No se necsita el store, ya que no se guarda el token en el registro
    try {
        const response = await api.post('/auth/register', formData);
        return {
            success: true,
        };
    } catch (error: any) {
        console.error('Error during registration:', error);
        
        if (!error.response) {
            // Network error or no response from server
            console.error('Network error or no response from server:', error);
            return {
                success: false,
                errors: {
                    non_field_errors: 'Error al conectar con el servidor. Por favor, verifica tu conexión a internet.'
                }
            };
        }
        //422 Response
        if (error.response && error.response.status === 422) {
            return {
                success: false,
                errors: error.response.data.errors,
            };
        }
        return {
            success: false,
            errors: {
                non_field_errors: 'Ocurrió un error inesperado, por favor intenta nuevamente.'
            }
        }
    }
}

//Temporal para pruebas, solo coloca lo que sea en el token
export const login = async (email: string, password: string): Promise<LoginResponse> => {
    const authStore = useAuthStore();
    try{
        const response = await api.post('auth/login', { email, password });
        authStore.setAccessToken(response.data.token);
        return {
            success: true,
        };
    }
    catch (error: any) {
        console.error('Error during login:', error);
        
        if (!error.response) {
            // Network error or no response from server
            console.error('Network error or no response from server:', error);
            return {
                success: false,
                errors: {
                    non_field_errors: 'Error al conectar con el servidor. Por favor, verifica tu conexión a internet.'
                }
            };
        }
        //401 or 422 Response
        if (error.response && (error.response.status === 401 || error.response.status === 422)) {
            
            return {
                success: false,
                errors: error.response.data.errors,
            };
        }
        return {
            success: false,
            errors: {
                non_field_errors: 'Ocurrió un error inesperado, por favor intenta nuevamente.'
            }
        }
    }
        
}

export const logout = async () => {
    try{
        const response = await api.post('/auth/logout');
        const authStore = useAuthStore();
        authStore.accessToken = null;
    }
    catch (error: any) {
        if (error.response && error.response.status === 401){
            // If the user is already logged out or token is invalid, we can just clear the token
            const authStore = useAuthStore();
            authStore.accessToken = null;
            return; // No need to throw an error, just clear the token
        }
        console.error('Error during logout:', error);
        throw error; // Re-throw the error to handle it in the calling function if needed
    }

};

export const refreshToken = async () : Promise<{ accessToken: string }> => {
    const authStore = useAuthStore();
    try {
        const response = await api.post('/auth/refresh-token');
        const accessToken = response.data.accessToken;
        authStore.setAccessToken(accessToken);
        return { accessToken };
    } catch (error) {
        console.error('Error refreshing token:', error);
        throw error;
    }
}

export const validateEmail = async (email: string): Promise<{ valid: boolean, error?: string }> => {
    try {
        const response = await api.post('/auth/validate-email', { email });
        return { valid: true };
    } catch (error: any) {
        console.error('Error validating email:', error);
        if (error.response && error.response.status === 422) {
            return { valid: false, error: error.response.data.error };
        }
        if(!error.response) {
            console.error('Network error or no response from server:', error);
            return { valid: false, error: 'Error al conectar con el servidor. Por favor, verifica tu conexión a internet.' };
        }
        return { valid: false, error: 'Ocurrió un error al validar el correo electrónico.' };
    }
};
