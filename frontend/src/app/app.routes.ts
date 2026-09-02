import { Routes } from '@angular/router';
import { AuthLayout } from './layout/auth-layout/auth-layout';
import { PublicLayout } from './layout/public-layout/public-layout';
import { DashboardLayout } from './layout/dashboard-layout/dashboard-layout';
import { NoPage } from './layout/no-page/no-page';

export const routes: Routes = [
  {
    path: '',
    component: PublicLayout,
  },
  {
    path: 'auth-login',
    component: AuthLayout,
  },
  {
    path:'dashboard',
    component:DashboardLayout,
  },
  {
    path:'**',
    component: NoPage,
  }
];
