
interface LoginResponseError {
    email?: string;
    password?: string;
    non_field_errors?: string;
}

export interface LoginResponse {
    success: boolean;
    errors?: LoginResponseError;
}
export interface RegisterResponseError {
    name?: string;
    surname?: string;
    email?: string;
    phone?: string;
    password?: string;
    password_confirmation?: string;
    terms_and_conditions?: string;
    privacy_policy?: string;
    non_field_errors?: string;
}
export interface RegisterResponse {
    success: boolean;
    errors?: RegisterResponseError;
}

export interface RegisterFormData {
    name: string;
    surname: string;
    email: string;
    phone: string;
    password: string;
    password_confirmation: string;
    terms_and_conditions: boolean;
    privacy_policy: boolean;
}
