<style scoped lang="scss">

.welcome-card{
    height: 100vh
}

.welcome-title{
    font-family: 'Poppins', sans-serif;
    font-size: 25px;
    font-weight: bold;
    color: #58525B;
    margin-bottom: 10px;
    margin-top: 20px;
}

.register-card{
    /*background-color: rgb(var(--v-theme-secondary));*/
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 30px 0;
    gap : 20px;
    height: 100%;
}

.form-card, .validate-email-card{

    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
}
.form-card{
    overflow-y: scroll;
}
.validate-email-card{
    gap: 20px;
}

.register-container {
    height: 100vh;
    padding: 0 5% 2% 5%;
}

.google-btn {
    border: 1px solid #676269;
    background-color: white !important;
    border-radius: 10px;
    box-shadow: none !important;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    color: #676269;
    padding: 12px 16px;
}

.validate-email-btn {
    font-family: 'Poppins', sans-serif;
    font-weight: bold;
    font-size: 16px;
    border-radius: 8px;
    padding: 12px 16px 29px 16px;
    margin-top: 10px;
}

.register-title{
    font-family: 'Poppins', sans-serif;
    font-size: 25px;
    color: #676269;
    margin-bottom: 10px;
}

.v-form{
    width: 70%;
}
.form-checkbox .Icon {
    cursor:pointer;
}

.password-btn {
    cursor: pointer;
}


</style>

<template>
<v-container class="register-container" fluid>
    <v-row style="height: 100%;" >
        <v-col cols="12" md="6" class="welcome-card d-none d-md-block">
        <p class="welcome-title">Pequeñas acciones, grandes cambios. 
            <br/> 
            ¡Bienvenido a la colonia de Donamiga!</p>
        <v-img src="/img/fondo_login.png " alt="Fondo de bienvenida" style ="height: 80%;" @click="showValidateEmail = !showValidateEmail"/>
        </v-col>
        <v-col cols="12" md="6" class="register-card">
            <img src="/icons/donamiga_logo.png" alt="Logo" width="180"/>
            <h1 class="register-title">Registrate</h1>

            <div v-if="showValidateEmail" class="validate-email-card">
                <v-btn class="google-btn" append-icon="mdi:google"> 
                <Icon icon="flat-color-icons:google" width="28" height="28" />
                Continua con Google
            </v-btn>
            <p class=" o-text">O</p>
            <v-form v-model="validEmailForm" ref="emailFormRef">
            <div color="text" class="text-left label">Correo electrónico</div>
            <v-text-field
                class="register-input"
                v-model="form.email"
                placeholder="Correo electrónico"
                density="compact"
                variant="outlined"
                type="email"
                append-inner-icon=""
                :rules ="emailRules"
                :error-messages="formErrors.email? [formErrors.email] : []"
                @blur="markFieldTouched('email')"
                @input="formErrors.email = ''"
                required>
                    <template #append-inner>
                        <Icon class="login-icon" icon="material-symbols-light:mail-outline" width="24" height="24" />
                    </template>
                </v-text-field>
                <v-btn 
                class="validate-email-btn"
                :loading="loading"
                @Click="validateEmail" 
                color="tertiary"
                :disabled="!validEmailForm || form.email === ''"
                >
                Verificar
                </v-btn>
            </v-form>
            </div>
            <div v-else class="form-card">
                <p class="instructions-text">
                Ingrese la siguiente información para que pueda registrarse.
                </p>
                <br/>
                <v-form v-model="validForm" ref="formRef">
                <div color="text" class="text-left label">Nombres</div>
                <v-text-field 
                    class="register-input"
                    v-model="form.name"
                    placeholder="Nombres"
                    density="compact"
                    variant="outlined"
                    type="text"
                    :rules="nameRules"
                    :error-messages="formErrors.name? [formErrors.name] : []"
                    required/>
                <div color="text" class="text-left label">Apellidos</div>
                <v-text-field 
                    class="register-input"
                    v-model="form.surname"
                    placeholder="Apellidos"
                    density="compact"
                    variant="outlined"
                    type="text"
                    :rules="surnameRules"
                    :error-messages="formErrors.surname? [formErrors.surname] : []"
                    required/>
                <div color="text" class="text-left label">Teléfono</div>
                <v-text-field 
                    class="register-input"
                    v-model="form.phone"
                    placeholder="Teléfono"
                    density="compact"
                    variant="outlined"
                    type="tel"
                    :maxlength="9"
                    @input="formatPhone"
                    :rules="phoneRules"
                    :error-messages="formErrors.phone? [formErrors.phone] : []"
                    required/>
                <div color="text" class="text-left label">Contraseña</div>
                <v-text-field 
                    class="register-input"
                    v-model="form.password"
                    placeholder="********"
                    density="compact"
                    variant="outlined"
                    :rules="passwordRules"
                    :error-messages="formErrors.password? [formErrors.password] : []"
                    :type="showPassword ? 'text' : 'password'"
                    append-inner-icon=""
                    required>
                        <template #append-inner>
                            <Icon class="password-btn login-icon" @Click="showPassword=!showPassword" :icon="showPassword ? 'fluent:eye-24-regular': 'fluent:eye-off-24-regular'"  width="24" height="24" />
                        </template>
                </v-text-field>
                <div color="text" class="text-left label">Confirmar contraseña</div>
                <v-text-field 
                    class="register-input"
                    v-model="form.password_confirmation"
                    placeholder="********"
                    density="compact"
                    variant="outlined"
                    :rules="passwordConfirmationRules"
                    :error-messages="formErrors.password_confirmation? [formErrors.password_confirmation] : []"
                    :type="showPassword ? 'text' : 'password'"
                    append-inner-icon=""
                    required>
                        <template #append-inner>
                            <Icon class="password-btn login-icon" @Click="showPassword=!showPassword" :icon="showPassword ? 'fluent:eye-24-regular': 'fluent:eye-off-24-regular'"  width="24" height="24" />
                        </template>
                </v-text-field>
                <v-checkbox
                    v-model="form.terms_and_conditions"
                    class="register-checkbox"
                    label="Acepto los términos y condiciones"
                    required
                    @click="form.terms_and_conditions = !form.terms_and_conditions"
                >
                    <template #input>
                    <Icon class="pointer" v-if="form.terms_and_conditions" icon="mdi:checkbox-marked" width="24" height="24" />
                    <Icon class="pointer" v-else icon="mdi:checkbox-blank-outline" width="24" height="24" />
                    </template>
            </v-checkbox>
                <v-checkbox
                    v-model="form.privacy_policy"
                    class="register-checkbox"
                    label="Acepto la política de privacidad"
                    required
                    @click ="form.privacy_policy = !form.privacy_policy"
                >
                    <template #input>
                    <Icon v-if="form.privacy_policy" icon="mdi:checkbox-marked" width="24" height="24" />
                    <Icon v-else icon="mdi:checkbox-blank-outline" width="24" height="24" />
                    </template>
            </v-checkbox>
                <v-btn 
                    class="register-btn"
                    :loading="loading"
                    @Click="register" 
                    color="secondary"
                    :disabled="!validForm || !form.terms_and_conditions || !form.privacy_policy"
                    >
                    Registrarse
                </v-btn>

                </v-form>
            </div>
        </v-col>
    </v-row>
</v-container>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive, watch } from 'vue';
import { useRouter } from 'vue-router';
import alertify from 'alertifyjs';
import { Icon } from '@iconify/vue';
import { validateEmail as validateEmailService, register as registerService } from '@/services/authService';
import { RegisterFormData, RegisterResponseError } from '@/types/authTypes';

const router = useRouter();
const showValidateEmail = ref(true);
const loading = ref(false);
const validEmailForm = ref(false);
const emailFormRef = ref();
const validForm = ref(false);
const formRef = ref();
const showPassword = ref(false);

const form = reactive<RegisterFormData>({
    email: '',
    name: '',
    surname: '',
    phone: '',
    password: '',
    password_confirmation: '',
    terms_and_conditions: false,
    privacy_policy: false,
});

const privacy_policy = ref(false);
const terms_and_conditions = ref(false);

const formErrors = reactive<RegisterResponseError>({
    email: '',
    name: '',
    surname: '',
    phone: '',
    password: '',
    password_confirmation: '',
    terms_and_conditions: '',
    privacy_policy: '',
    non_field_errors: '',
});

const fieldsTouched = reactive({
    email: false,
    name: false,
    surname: false,
    phone: false,
    password: false,
    password_confirmation: false,
});

const markFieldTouched = (field: keyof typeof fieldsTouched) => {
    fieldsTouched[field] = true;
};

const markFormTouched = () => {
    Object.keys(fieldsTouched).forEach((key) => {
        fieldsTouched[key as keyof typeof fieldsTouched] = true;
    });
};


const emailRules = [
    (v: string) => !!v || 'El correo electrónico es obligatorio',
    (v: string) => /.+@.+\..+/.test(v) || 'El correo electrónico debe ser válido',
]
const nameRules = [
    (v: string) => !!v || 'El nombre es obligatorio',
    (v: string) => v.length >= 3 || 'El nombre debe tener al menos 3 caracteres',
]
const surnameRules = [
    (v: string) => !!v || 'El apellido es obligatorio',
    (v: string) => v.length >= 3 || 'El apellido debe tener al menos 3 caracteres',
]
const phoneRules = [
    (v: string) => !!v || 'El teléfono es obligatorio',
    (v: string) => /^\d{4}-\d{4}$/.test(v) || 'El teléfono debe tener el formato 0000-0000',
]

const passwordRules = [
    (v: string) => !!v || 'La contraseña es obligatoria',
    // Al menos 8 caracteres, al menos una letra mayúscula, al menos una letra minúscula, al menos un número
    (v: string) => (v.length >= 8 && /[A-Z]/.test(v) && /[a-z]/.test(v) && /\d/.test(v)) || 'La contraseña debe tener al menos 8 caracteres, una letra mayúscula, una letra minúscula y un número',
]

const passwordConfirmationRules = [
    (v: string) => !!v || 'La confirmación de contraseña es obligatoria',
    (v: string) => v === form.password || 'Las contraseñas no coinciden',
]


const validateEmail = async () => {
    markFormTouched();
    loading.value = true;
    if (!form.email) {
        formErrors.email = 'El correo electrónico es obligatorio';
        loading.value = false;
        return;
    }

    try {
        const response = await validateEmailService(form.email);

        if (response.valid){
            showValidateEmail.value = false;
            loading.value = false;
            return;
        }
        if (response.error) {
            formErrors.email = response.error;
            loading.value = false;
            return;
        }

    } catch (error) {
        formErrors.email = 'Error al verificar el correo electrónico';
    }
    loading.value = false;
}

const register = async () => {
    loading.value = true;
    markFormTouched();
    const { valid } = await formRef.value.validate();
    if (valid) {
        try{
        // Eliminar guiones del teléfono antes de enviar
        form.phone = unformatPhone(form.phone);
        // Llamar al servicio de registro
        const response = await registerService(form);
        if (response.success) {
            alertify.success('Registro exitoso. Por favor, verifica tu correo electrónico.');
            router.push('/login'); 
            } else{
                if (response.errors?.name){
                    formErrors.name = response.errors.name;
                } else {
                    formErrors.name = '';
                }
                if (response.errors?.surname){
                    formErrors.surname = response.errors.surname;
                } else {
                    formErrors.surname = '';
                }
                if (response.errors?.phone){
                    formErrors.phone = response.errors.phone;
                } else {
                    formErrors.phone = '';
                }
                if (response.errors?.email){
                    formErrors.email = response.errors.email;
                    showValidateEmail.value = true; // Volver a mostrar el formulario de validación de correo electrónico
                } else {
                    formErrors.email = '';
                }
                if (response.errors?.password){
                    formErrors.password = response.errors.password;
                } else {
                    formErrors.password = '';
                }
                if (response.errors?.password_confirmation){
                    formErrors.password_confirmation = response.errors.password_confirmation;
                } else {
                    formErrors.password_confirmation = '';
                }
                if (response.errors?.terms_and_conditions){
                    formErrors.terms_and_conditions = response.errors.terms_and_conditions;
                } else {
                    formErrors.terms_and_conditions = '';
                }
                if (response.errors?.privacy_policy){
                    formErrors.privacy_policy = response.errors.privacy_policy;
                } else {
                    formErrors.privacy_policy = '';
                }
                if (response.errors?.non_field_errors){
                    alertify.error(response.errors.non_field_errors);
                }
            }
    }
    catch (error) {
        alertify.error('Error al registrarse. Por favor, inténtelo de nuevo más tarde.');
    }
    finally{
        loading.value = false;
    
    }
    } else {
        alertify.error('Poooor favor, complete todos los campos correctamente.');
        loading.value = false;
    }
}

const formatPhone = ()=>{
    const phone = form.phone.replace(/\D/g, ''); // Elimina todos los caracteres no numéricos
    if (phone.length > 4) {
        form.phone =  `${phone.slice(0, 4)}-${phone.slice(4)}`;
    }
    else{
        form.phone = phone;
    }
}

const unformatPhone = (phone: string) => {
    return phone.replace(/-/g, ''); // Elimina los guiones
}
</script>