import { Component, signal } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { PIcon } from '@primeicons/angular/p-icon';

interface NavItem {
  label: string;
  icon: string;
  route: string;
  exact?: boolean;
}

@Component({
  selector: 'app-dashboard-menu',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, PIcon],
  template: `
    <div class="min-h-screen bg-surface-50">
      @if (mobileOpen()) {
        <button type="button" aria-label="Close menu" class="fixed inset-0 z-30 bg-surface-950/40 lg:hidden" (click)="mobileOpen.set(false)"></button>
      }

      <aside
        class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-surface-200 bg-white px-4 py-5 transition-transform lg:transurface-x-0"
        [class.-transurface-x-full]="!mobileOpen()"
      >
        <div class="mb-8 flex items-center justify-between px-2">
          <a routerLink="/dashboard" class="flex items-center gap-3" (click)="mobileOpen.set(false)">
            <img src="/img/minty-icon.png" alt="Minty" class="h-10 w-10 object-contain" />
            <span class="text-xl font-extrabold tracking-tight text-surface-900">Minty</span>
          </a>
        <button aria-label="Close menu" class="rounded-lg p-2 text-surface-500 lg:hidden" (click)="mobileOpen.set(false)"><svg pIcon="times" size="20" aria-hidden="true"></svg></button>
        </div>

        <nav class="flex-1 space-y-7 overflow-y-auto">
          @for (group of navGroups; track group.label) {
            <section>
              <p class="mb-2 px-3 text-xs font-bold uppercase tracking-widest text-surface-400">{{ group.label }}</p>
              <div class="space-y-1">
                @for (item of group.items; track item.route) {
                  <a
                    [routerLink]="item.route"
                    routerLinkActive="bg-primary-50 text-primary-700"
                    [routerLinkActiveOptions]="{ exact: item.exact ?? false }"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 font-semibold text-surface-600 transition hover:bg-surface-100 hover:text-surface-900"
                    (click)="mobileOpen.set(false)"
                  >
                    <svg [pIcon]="item.icon" size="20" class="shrink-0" aria-hidden="true"></svg>
                    <span>{{ item.label }}</span>
                  </a>
                }
              </div>
            </section>
          }
        </nav>

        <a routerLink="/dashboard/profile" class="mt-5 flex items-center gap-3 rounded-2xl border border-surface-200 p-3 hover:bg-surface-50">
          <span class="grid h-10 w-10 place-items-center rounded-full bg-primary-100 font-bold text-primary-700">GS</span>
          <span class="min-w-0 flex-1">
            <strong class="block truncate text-sm text-surface-800">Gustavo Silva</strong>
            <small class="block truncate text-surface-500">gustavo@email.com</small>
          </span>
          <svg pIcon="chevron-right" size="16" class="shrink-0 text-surface-400" aria-hidden="true"></svg>
        </a>
      </aside>

      <div class="lg:pl-72">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-surface-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8">
          <button type="button" class="grid h-10 w-10 place-items-center rounded-xl border border-surface-200 text-xl lg:hidden" aria-label="Open menu" (click)="mobileOpen.set(true)"><svg pIcon="bars" size="20" aria-hidden="true"></svg></button>
          <p class="hidden text-sm font-medium text-surface-500 sm:block">Your finances, all in one place.</p>
          <div class="ml-auto flex items-center gap-3">
            <a routerLink="/dashboard/profile" class="grid h-10 w-10 place-items-center rounded-full bg-primary-600 text-sm font-bold text-white">GS</a>
          </div>
        </header>
        <ng-content />
      </div>
    </div>
  `,
})
export class DashboardMenu {
  readonly mobileOpen = signal(false);

  readonly navGroups = [
    {
      label: 'Main',
      items: [
        { label: 'Overview', icon: 'home', route: '/dashboard', exact: true },
        { label: 'Accounts', icon: 'wallet', route: '/dashboard/accounts' },
        { label: 'Transfer', icon: 'send', route: '/dashboard/transfer' },
        { label: 'Categories', icon: 'tags', route: '/dashboard/categories' },
      ] satisfies NavItem[],
    },
    {
      label: 'Organization',
      items: [{ label: 'Archived', icon: 'inbox', route: '/dashboard/archived' }] satisfies NavItem[],
    },
    {
      label: 'Account',
      items: [
        { label: 'Profile', icon: 'user', route: '/dashboard/profile' },
        { label: 'Sign out', icon: 'sign-out', route: '/auth-login' },
      ] satisfies NavItem[],
    },
  ];
}
