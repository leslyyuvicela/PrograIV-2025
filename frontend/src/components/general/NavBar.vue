<style lang="scss" scoped>

$primary: rgb(var(--v-theme-primary));
$secondary: rgb(var(--v-theme-secondary));
.login-btn {
    background-color: $secondary;
    color: white;
    border-radius: 4px;
    padding: 8px 16px;
    font-weight: bold;
    margin: 0% 4px;
}
.register-button {
    border: 1px solid $secondary;
    color: $secondary;
    border-radius: 4px;
    padding: 8px 16px;
    font-weight: bold;
    background-color: transparent;
    margin: 0 8px;
}

.active-link {
    border-bottom: 3px solid $primary;
    background-color: white;
    color: $primary;           
}


.logout-dialog {
  background-color: #ffffff;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  font-family: 'Montserrat', sans-serif;

  .message {
    font-size: 16px;
    color: #676269;
    text-align: center;
  }

  .cancel-button {
    background-color: #ffffff;
    color: #f41146;
    border: 1px solid #f41146;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 20px;
    padding: 8px 16px;
    transition: background-color 0.3s;

    &:hover {
      background-color: #f2c1f6;
    }
  }

  .logout-button {
    background-color: #f41146;
    color: #ffffff;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 20px;
    padding: 8px 16px;
    transition: background-color 0.3s;

    &:hover {
      background-color: #ec5674;
    }
  }
}

</style>

<template>
    <v-app-bar app>
        <!-- Logo (guardado en /public/icons/) -->
        <v-img src="/icons/donamiga_logo.png" alt="Logo" width="100" height="50" class="mr-4" to="/" />
        <v-spacer />
        <!-- Navigation Links -->
        <template v-if="!isMobile">
            <v-btn text to="/" :class="{ 'active-link': currentRoute.path === '/' }">
                Inicio
            </v-btn>
            <v-btn text to="/about" :class="{ 'active-link': currentRoute.path === '/about' }">
                Sobre nosotros
            </v-btn>
            <v-btn text to="/fundraisings">Donar</v-btn>
            <v-btn text @click="createFundraising" :class="{ 'active-link': currentRoute.path === '/create-fundraising' }">
                Recaudar</v-btn>
            <v-btn to="/notifications" v-if="authStore.isAuthenticated" color="primary">
                <Icon icon="mdi:bell" width="24" height="24" />
            </v-btn>
            <v-menu offset-y v-if="authStore.isAuthenticated">
                <template #activator="{ props }">
                    <v-btn text v-bind="props" color="primary">
                        <Icon icon="mdi:account-circle" width="24" height="24"/>
                        <Icon icon="mdi:chevron-down" width="24" height="24" />
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item to="/profile">
                        <v-list-item-title>Perfil</v-list-item-title>
                    </v-list-item>
                    <v-list-item to="/my-donations">
                        <v-list-item-title>Mis donaciones</v-list-item-title>
                    </v-list-item>
                    <v-list-item to="/my-fundraisings">
                        <v-list-item-title>Mis recaudaciones</v-list-item-title>
                    </v-list-item>
                    <v-list-item to="/conversations">
                        <v-list-item-title>Conversaciones</v-list-item-title>
                    </v-list-item>
                    <v-list-item to="/help">
                        <v-list-item-title>Ayuda</v-list-item-title>
                    </v-list-item>
                    <v-list-item to="/settings">
                        <v-list-item-title>Ajustes</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="showLogoutDialog = true">
                        <v-list-item-title>Cerrar sesión</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
            <v-btn v-if="!authStore.isAuthenticated" class="login-btn" to ="/login">
                Iniciar sesión
            </v-btn>
            <v-btn class="register-button" to="/register" v-if="!authStore.isAuthenticated">
                Registrarse
            </v-btn>

        </template>
        <v-app-bar-nav-icon v-if="isMobile" @click="drawer = !drawer">
            <Icon icon="mdi:menu" width="24" height="24" color="purple" />
        </v-app-bar-nav-icon>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" temporary right width="300">
        <v-list>
            <v-list-item to="/">
                <v-list-item-title class="text-left">Inicio</v-list-item-title>
            </v-list-item>
            <v-list-item to="/about">
                <v-list-item-title class="text-left">Sobre nosotros</v-list-item-title>
            </v-list-item>
            <v-list-item to="/fundraisings">
                <v-list-item-title class="text-left">Donar</v-list-item-title>
            </v-list-item>
            <v-list-item @click="createFundraising">
                <v-list-item-title class="text-left">Recaudar</v-list-item-title>
            </v-list-item>
            <v-list-item to="/notifications" v-if="authStore.isAuthenticated">
                <v-list-item-title class="text-left">Notificaciones</v-list-item-title>
            </v-list-item>
            <v-list-group v-model="groupAccount" class="text-left" v-if="authStore.isAuthenticated">
                <template #activator="{ props }">
                    <v-list-item v-bind="props" class="text-left">

                        <v-list-item-title class="d-flex align-center">
                            <Icon icon="mdi:account-circle" width="24" height="24" color="purple" />
                            <span class="ml-2 mr-2">Cuenta</span>
                            <Icon icon="mdi:chevron-down" width="24" height="24" color="purple" />
                        </v-list-item-title>
                    </v-list-item>
                </template>
                <v-list-item to="/profile">
                    <v-list-item-title>Perfil</v-list-item-title>
                </v-list-item>
                <v-list-item to="/my-donations">
                    <v-list-item-title>Mis donaciones</v-list-item-title>
                </v-list-item>
                <v-list-item to="/my-fundraisings">
                    <v-list-item-title>Mis recaudaciones</v-list-item-title>
                </v-list-item>
                <v-list-item to="/conversations">
                    <v-list-item-title>Conversaciones</v-list-item-title>
                </v-list-item>
                <v-list-item to="/help">
                    <v-list-item-title>Ayuda</v-list-item-title>
                </v-list-item>
                <v-list-item to="/settings">
                    <v-list-item-title>Ajustes</v-list-item-title>
                </v-list-item>
                <v-list-item @click="showLogoutDialog = true">
                    <v-list-item-title>Cerrar sesión</v-list-item-title>
                </v-list-item>

            </v-list-group>
            <v-list-item v-if="!authStore.isAuthenticated" to="/login">
                <v-list-item-title class="text-left">
                    Iniciar sesión
                </v-list-item-title>
            </v-list-item>
            <v-list-item to="/register" v-if="!authStore.isAuthenticated">
                <v-list-item-title class="text-left">
                    Registrarse
                </v-list-item-title>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>

      <v-dialog v-model="showLogoutDialog" max-width="360" persistent>
    <v-card class="logout-dialog pa-6">
      <div class="message">
        ¿Estás seguro de que deseas cerrar sesión?
      </div>

      <v-row class="mt-6" justify="space-between">
        <v-btn class="cancel-button" @click="showLogoutDialog = false">
          Cancelar
        </v-btn>
        <v-btn class="logout-button" @click="logout">
          Cerrar sesión
        </v-btn>
      </v-row>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useDisplay } from 'vuetify'
import {Icon} from '@iconify/vue'
import { useAuthStore } from '@/stores/authStore'
import { useFundraisingStore } from '@/stores/fundraisingStore'
import { login, logout as logoutService } from '@/services/authService'
import { useRouter } from 'vue-router'
import alertify from 'alertifyjs'

const router = useRouter()
const currentRoute = computed(() => router.currentRoute.value)

const authStore = useAuthStore()
const fundraisingStore = useFundraisingStore()

const drawer = ref(false)

const groupAccount = ref(false)

const { smAndDown } = useDisplay()
const isMobile = computed(() => smAndDown.value)

const showLogoutDialog = ref(false)

const logout = async () => {
    try {
        await logoutService()
        showLogoutDialog.value = false
        router.push('/login')
    }
    catch(error: any){
        alertify.error('Error al cerrar sesión: ' + error.message)
        console.error('Error al cerrar sesión:', error)
    }
}

const createFundraising = () => {
    if (!authStore.isAuthenticated) {
        alertify.error('Debes iniciar sesión para crear una recaudación')
        router.push('/login')
    } else {
        fundraisingStore.createFundraising()
        router.push({ name: 'create-fundraising' })
    }
}
</script>