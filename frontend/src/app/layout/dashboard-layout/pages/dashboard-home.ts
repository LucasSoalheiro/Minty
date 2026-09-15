import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { accounts, money, transactions } from '../mock/mock-data';

@Component({
  standalone: true,
  imports: [RouterLink],
  template: `
    <section class="space-y-8">
      <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="mb-1 font-semibold text-primary-600">Tuesday, September 15</p>
          <h1 class="text-3xl font-extrabold tracking-tight text-surface-900">Hi, Gustavo</h1>
          <p class="mt-2 text-surface-500">Here is an overview of your finances.</p>
        </div>
        <select class="rounded-xl border border-surface-200 bg-white px-4 py-3 font-semibold text-surface-700 outline-none focus:border-primary-500">
          <option>All accounts</option>
          @for (account of accounts; track account.id) { <option>{{ account.name }}</option> }
        </select>
      </header>

      <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 to-primary-700 p-6 shadow-lg shadow-primary-900/10 sm:col-span-2">
          <div class="absolute -right-12 -top-16 h-44 w-44 rounded-full bg-white/10"></div>
          <p class="text-sm font-medium text-primary-50">Total balance</p>
          <strong class="mt-3 block text-4xl font-extrabold tracking-tight text-white">{{ money(totalBalance) }}</strong>
          <p class="mt-5 text-sm text-primary-100">Across {{ accounts.length }} active accounts</p>
        </article>
        <article class="rounded-3xl border border-surface-200 bg-white p-6">
          <span class="grid h-11 w-11 place-items-center rounded-2xl bg-amber-50 text-xl">◷</span>
          <p class="mt-5 text-sm font-medium text-surface-500">Pending</p>
          <strong class="mt-1 block text-3xl font-extrabold text-surface-900">1</strong>
          <p class="mt-2 text-sm text-amber-600">Needs attention</p>
        </article>
        <article class="rounded-3xl border border-surface-200 bg-white p-6">
          <span class="grid h-11 w-11 place-items-center rounded-2xl bg-primary-50 text-xl">↗</span>
          <p class="mt-5 text-sm font-medium text-surface-500">Incoming this month</p>
          <strong class="mt-1 block text-2xl font-extrabold text-surface-900">R$5,276.00</strong>
          <p class="mt-2 text-sm text-primary-600">+8.4% this month</p>
        </article>
      </div>

      <section>
        <div class="mb-4 flex items-center justify-between">
          <h2 class="text-xl font-bold text-surface-900">Quick actions</h2>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
          @for (action of quickActions; track action.label) {
            <a [routerLink]="action.route" class="group flex items-center gap-3 rounded-2xl border border-surface-200 bg-white p-4 transition hover:-transurface-y-0.5 hover:border-primary-300 hover:shadow-md">
              <span class="grid h-11 w-11 place-items-center rounded-xl bg-primary-50 text-xl text-primary-700">{{ action.icon }}</span>
              <span class="font-bold text-surface-700 group-hover:text-primary-700">{{ action.label }}</span>
            </a>
          }
        </div>
      </section>

      <div class="grid gap-6 xl:grid-cols-[1fr_1.35fr]">
        <section class="rounded-3xl border border-surface-200 bg-white p-5 sm:p-6">
          <div class="mb-5 flex items-center justify-between">
            <div><h2 class="text-xl font-bold text-surface-900">My accounts</h2><p class="text-sm text-surface-500">Active accounts</p></div>
            <a routerLink="/dashboard/accounts" class="text-sm font-bold text-primary-600 hover:text-primary-700">View all</a>
          </div>
          <div class="space-y-3">
            @for (account of accounts; track account.id; let index = $index) {
              <a [routerLink]="['/dashboard/accounts', account.id]" class="flex items-center gap-4 rounded-2xl border border-surface-100 p-4 transition hover:bg-surface-50">
                <span class="grid h-11 w-11 place-items-center rounded-xl font-bold" [class]="index === 0 ? 'bg-primary-100 text-primary-700' : 'bg-surface-100 text-surface-600'">{{ account.name.charAt(0) }}</span>
                <span class="min-w-0 flex-1"><strong class="block truncate text-surface-800">{{ account.name }}</strong><small class="text-surface-500">Active account</small></span>
                <strong class="text-surface-900">{{ money(account.balance) }}</strong>
              </a>
            }
          </div>
        </section>

        <section class="rounded-3xl border border-surface-200 bg-white p-5 sm:p-6">
          <div class="mb-5 flex items-center justify-between">
            <div><h2 class="text-xl font-bold text-surface-900">Recent activity</h2><p class="text-sm text-surface-500">Main wallet</p></div>
            <a [routerLink]="['/dashboard/accounts', 'acc-carteira', 'transactions']" class="text-sm font-bold text-primary-600">View statement</a>
          </div>
          <div class="divide-y divide-surface-100">
            @for (transaction of transactions.slice(0, 4); track transaction.id) {
              <div class="flex items-center gap-3 py-3.5">
                <span class="grid h-10 w-10 place-items-center rounded-full" [class]="transaction.type === 'INFLOW' ? 'bg-primary-50 text-primary-600' : 'bg-surface-100 text-surface-600'">{{ transaction.type === 'INFLOW' ? '↓' : '↑' }}</span>
                <span class="min-w-0 flex-1"><strong class="block truncate text-sm text-surface-800">{{ transaction.description }}</strong><small class="text-surface-500">{{ transaction.category }} · {{ transaction.date }}</small></span>
                <span class="text-right"><strong class="block text-sm" [class]="transaction.type === 'INFLOW' ? 'text-primary-600' : 'text-surface-800'">{{ transaction.type === 'INFLOW' ? '+' : '-' }} {{ money(transaction.amount) }}</strong><small [class]="transaction.status === 'PENDING' ? 'text-amber-600' : 'text-surface-400'">{{ statusLabel(transaction.status) }}</small></span>
              </div>
            }
          </div>
        </section>
      </div>
    </section>
  `,
})
export class DashboardHome {
  readonly accounts = accounts;
  readonly transactions = transactions;
  readonly money = money;
  readonly totalBalance = accounts.reduce((sum, account) => sum + account.balance, 0);
  readonly quickActions = [
    { label: 'Deposit', icon: '↓', route: '/dashboard/accounts/acc-carteira' },
    { label: 'Withdraw', icon: '↑', route: '/dashboard/accounts/acc-carteira' },
    { label: 'Transfer', icon: '↗', route: '/dashboard/transfer' },
    { label: 'New account', icon: '+', route: '/dashboard/accounts/new' },
  ];
  statusLabel(status: string) { return ({ DONE: 'Completed', PENDING: 'Pending', CANCELLED: 'Cancelled' } as Record<string, string>)[status]; }
}
