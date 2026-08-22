import { ApplicationConfig, provideBrowserGlobalErrorListeners } from '@angular/core';
import { provideRouter } from '@angular/router';
import { providePrimeNG } from 'primeng/config';
import MyPreset from './mypreset';
import { routes } from './app.routes';

export const appConfig: ApplicationConfig = {
  providers: [
    provideBrowserGlobalErrorListeners(),
    provideRouter(routes),
    providePrimeNG({
      theme: {
        preset: MyPreset,
        options: {
          darkModeSelector: '.my-app-dark',
        },
      },
      license:
        'eyJpZCI6ImZlZGEzN2QxLWY1YzgtNGU3My1hMmIyLTFhYTVjNjhkODBmZSIsInByb2R1Y3QiOiJwcmltZXVpIiwidGllciI6ImNvbW11bml0eSIsInR5cGUiOiJkZXYiLCJpYXQiOjE3ODczNTQ1NDQsImV4cCI6MTgxODg5MDU0NH0.J6ZgH0xS5tXuELh34f0Hw0MGMDOQEUlP7_dqR5YSWhyVC35OfxO8Z9t38CVtDjouZly6wat-GVwW5tK94OIbAA',
    }),
  ],
};
