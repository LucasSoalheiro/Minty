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
    children: [
      { path: '', loadComponent: () => import('./layout/dashboard-layout/pages/dashboard-home').then((m) => m.DashboardHome) },
      { path: 'accounts', loadComponent: () => import('./layout/dashboard-layout/pages/account/accounts').then((m) => m.Accounts) },
      { path: 'accounts/new', loadComponent: () => import('./layout/dashboard-layout/pages/account/account-create').then((m) => m.AccountCreate) },
      { path: 'accounts/:accountId', loadComponent: () => import('./layout/dashboard-layout/pages/account/account-details').then((m) => m.AccountDetails) },
      { path: 'accounts/:accountId/transactions', loadComponent: () => import('./layout/dashboard-layout/pages/account/account-transactions').then((m) => m.AccountTransactions) },
      { path: 'transfer', loadComponent: () => import('./layout/dashboard-layout/pages/transfer').then((m) => m.Transfer) },
      { path: 'categories', loadComponent: () => import('./layout/dashboard-layout/pages/categories').then((m) => m.Categories) },
      { path: 'archived', loadComponent: () => import('./layout/dashboard-layout/pages/archived').then((m) => m.Archived) },
      { path: 'profile', loadComponent: () => import('./layout/dashboard-layout/pages/profile').then((m) => m.Profile) },
    ],
  },
  {
    path:'**',
    component: NoPage,
  }
];
