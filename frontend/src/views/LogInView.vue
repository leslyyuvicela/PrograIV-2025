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

.login-card{
    /*background-color: rgb(var(--v-theme-secondary));*/
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    padding: 30px;
    gap : 20px;
}

.login-container {
    height: 100%;
    padding: 0 5%;
}

.login-title{
    font-family: 'Poppins', sans-serif;
    font-size: 25px;
    color: #676269;
    margin-bottom: 10px;
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

.o-text{
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    color: #676269;
}
.register-btn {
    background: none !important;
    border: none !important;
    box-shadow: none !important;
    color: #676269;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
}
.login-btn {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    border-radius: 8px;
    padding: 12px 16px 29px 16px;
    background-color: #F41146 !important;
    margin-top: 10px;
}

.register-btn span {
    font-weight: bold;
    color: #73d5f5;
}

.label{
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    margin-bottom: 1rem;
    color: #676269;
}

.password-btn {
    cursor: pointer;
}

.password-btn:hover {
    transition: transform 0.3s ease;
    transform: scale(1.1);
}

.forgot-link {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    color: #73d5f5;
    cursor: pointer;
    margin-bottom: 10px;
}

.login-icon{
    color: #B1B1B1;
}
    
</style>

<template>
<v-container class="login-container" fluid>
    <v-row style="height: 100%;" >
        <v-col cols="12" md="6" class="welcome-card d-none d-md-block">
        <p class="welcome-title">Pequeñas acciones, grandes cambios. 
            <br/> 
            ¡Bienvenido a la colonia de Donamiga!</p>
        <v-img src="/img/fondo_login.png " alt="Fondo de bienvenida" style ="height: 80%;"/>
        </v-col>
        <v-col cols="12" md="6" class="login-card">
            <img src="/icons/donamiga_logo.png" alt="Logo" width="180"/>
            <h1 class="login-title">Inicia sesión</h1>
            <!--Botón con un icono del logo de google-->
            <v-btn class="google-btn" append-icon="mdi:google"> 
                <Icon icon="flat-color-icons:google" width="28" height="28" />
                Continua con Google
            </v-btn>
            <p class=" o-text">O</p>
            <v-form v-model="validForm" ref="formRef">
                <div color="text" class="text-left label">Correo electrónico</div>
                <v-text-field 
                v-model="email"
                placeholder="Correo electrónico"
                density="compact"
                variant="outlined"
                type="email"
                append-inner-icon=""
                :rules ="emailTouched ? emailRules :altEmailRules"
                :error-messages="emailError? [emailError] : []"
                @blur="markTouched"
                @input="emailError = ''"
                required>
                    <template #append-inner>
                        <Icon class="login-icon" icon="material-symbols-light:mail-outline" width="24" height="24" />
                    </template>
                </v-text-field>
                <div class="text-left label">Contraseña</div>
                <v-text-field 
                v-model="password"
                placeholder="********"
                density="compact"
                variant="outlined"
                :rules="passwordRules"
                :error-messages="passwordError? [passwordError] : []"
                @input="passwordError = ''"
                :type="showPassword ? 'text' : 'password'"
                append-inner-icon=""
                required>
                    <template #append-inner>
                        <Icon class="password-btn login-icon" @Click="showPassword=!showPassword" :icon="showPassword ? 'fluent:eye-24-regular': 'fluent:eye-off-24-regular'"  width="24" height="24" />
                    </template>
            </v-text-field>
                <p text class="text-right forgot-link" @click="forgotPassword">¿Olvidaste tu contraseña?</p>

                <v-btn 
                class="login-btn"
                :loading="loading"
                 @Click="login" 
                 color="secondary">
                 Iniciar sesión
                </v-btn>
                <br/>
                <v-btn class = "register-btn" to="/register">
                    ¿No tienes una cuenta? 
                    <span>Regístrate</span>
                </v-btn>
            </v-form>
        </v-col>
    </v-row>
</v-container>
</template>


<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { ref, computed } from 'vue';
import { login as loginService } from '@/services/authService';
import alertify from 'alertifyjs';
import { useRouter } from 'vue-router';
const router = useRouter();

const email = ref('');
const password = ref('');

const validForm = ref(false);
const formRef = ref();
const showPassword = ref(false);
const loading = ref(false);

const emailTouched = ref(false);
const markTouched = () => {
  emailTouched.value = true
}

const emailRules = [
        (v: string) => !!v || 'El correo electrónico es obligatorio',
        (v: string) => /.+@.+\..+/.test(v) || 'El correo electrónico debe ser válido',
    ];

const altEmailRules = [
    (v: string) => !!v || 'El correo electrónico es obligatorio',
]

const passwordRules = [
    (v: string) => !!v || 'La contraseña es obligatoria',
    
]
const emailError = ref('');
const passwordError = ref('');

const forgotPassword = () => {
    router.push('/forgot-password'); // Redirigir a la página de recuperación de contraseña
}

const login = async () => {
    loading.value = true;
    markTouched(); 
    const {valid} = await formRef.value.validate();
    if(valid) {
    try {
        const response = await loginService(email.value, password.value);
        if (response.success){
            router.push('/'); // Redirigir a la página principal
        }
        else{
            if (response.errors?.email) {
                emailError.value = response.errors.email;
            } else {
                emailError.value = '';
            }
            if (response.errors?.password) {
                passwordError.value = response.errors.password;
            } else {
                passwordError.value = '';
            }
            if (response.errors?.non_field_errors) {
                alertify.error(response.errors?.non_field_errors)
            }

        }
    } catch (error) {
        // Manejar el error de inicio de sesión
        console.error('Error al iniciar sesión:', error);
        alertify.error('Error al iniciar sesión. Por favor, inténtalo de nuevo más tarde.');
    } finally {
        loading.value = false;
    }
}
loading.value = false;
}
</script>