// src/plugins/vuetify.ts
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

export const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'lightTheme',
    themes: {
      lightTheme:{
        dark: false,
        colors: {
          primary: '#8F00B3', // Purple
          secondary: '#F41146', // Red
          tertiary: '#73D5F5', // Light Blue
          primaryLight: '#E987FF', // Light Purple
          secondaryLight: '#EC5674', // Light Red
          tertiaryLight: '#C0E7F4', // Very Light Blue
          background: '#FFFFFF', // White
          surface: '#F5F5F5', // Light Gray
          text: '#676269', // Dark Gray
          textLight: '#FFFFFF', // White Text
          textAccent: '#8F00B3', // Accent Purple
          textError: '#F41146', // Accent Red
          error: '#FF5252', // Error Red
          success: '#29B829', // Success Green
          successLight: '#7CE37C', // Light Success Green
        }
      }
    },
  },
  defaults: {
    VBtn: {

    }
  },
})
