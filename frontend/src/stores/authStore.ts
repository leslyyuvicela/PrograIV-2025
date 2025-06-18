import { defineStore } from "pinia";

interface User {
  id: string;
  names: string;
  surnames: string;
  email: string;
  phone: string;
  role: string | null;
}


export const useAuthStore = defineStore("auth", {
  state: () => ({
    accessToken: null as string | null,
    refreshToken: null as string | null,
    user: null as User | null,
  }),
    getters: {
        isAuthenticated: (state) => !!state.accessToken,
        getEmail: (state) => state.user?.email || "",
    },
    actions: {
        logout() {
            this.accessToken = null;
            this.refreshToken = null;
            this.user = null;
        },
        setAccessToken(token: string) {
            this.accessToken = token;
        },
        setRefreshToken(token: string) {
            this.refreshToken = token;
        },
    },
    persist: true,
});
