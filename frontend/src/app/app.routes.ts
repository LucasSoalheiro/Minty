import { Routes } from '@angular/router';
import { AuthLayout } from './layout/auth-layout/auth-layout';
import { PublicLayout } from './layout/public-layout/public-layout';
import { WorkspaceLayout } from './layout/workspace-layout/workspace-layout';
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
    path:'workspace',
    component:WorkspaceLayout,
  },
  {
    path:'**',
    component: NoPage,
  }
];
