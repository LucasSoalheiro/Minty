import { definePreset } from '@primeuix/themes';
import Aura from '@primeuix/themes/aura';

// Shared palette for PrimeNG and the primary/surface Tailwind utilities.
const MyPreset = definePreset(Aura, {
  semantic: {
    primary: {
      50: '{emerald.50}',
      100: '{emerald.100}',
      200: '{emerald.200}',
      300: '{emerald.300}',
      400: '{emerald.400}',
      500: '{emerald.500}',
      600: '{emerald.600}',
      700: '{emerald.700}',
      800: '{emerald.800}',
      900: '{emerald.900}',
      950: '{emerald.950}',
    },
    colorScheme: {
      light: {
        surface: {
          0: '#ffffff',
          50: '{slate.50}', 100: '{slate.100}', 200: '{slate.200}',
          300: '{slate.300}', 400: '{slate.400}', 500: '{slate.500}',
          600: '{slate.600}', 700: '{slate.700}', 800: '{slate.800}',
          900: '{slate.900}', 950: '{slate.950}',
        },
        primary: {
          color: '{primary.500}',
          contrastColor: '#ffffff',
          hoverColor: '{primary.600}',
          activeColor: '{primary.700}',
        },
      },
      dark: {
        surface: {
          0: '#ffffff',
          50: '{slate.50}', 100: '{slate.100}', 200: '{slate.200}',
          300: '{slate.300}', 400: '{slate.400}', 500: '{slate.500}',
          600: '{slate.600}', 700: '{slate.700}', 800: '{slate.800}',
          900: '{slate.900}', 950: '{slate.950}',
        },
        primary: {
          color: '{primary.400}',
          contrastColor: '{surface.950}',
          hoverColor: '{primary.300}',
          activeColor: '{primary.200}',
        },
      },
    },
  },
});

export default MyPreset;
