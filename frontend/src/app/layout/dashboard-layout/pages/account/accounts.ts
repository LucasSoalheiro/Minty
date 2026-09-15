import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';
import { accounts, money } from '../../mock/mock-data';

@Component({
  standalone: true,
  imports: [RouterLink],
  template: `
    <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
      <div><p class="font-semibold text-primary-600">Organization</p><h1 class="text-3xl font-extrabold text-surface-900">My accounts</h1><p class="mt-2 text-surface-500">Manage your balances and transactions.</p></div>
      <a routerLink="/dashboard/accounts/new" class="rounded-xl bg-primary-600 px-5 py-3 text-center font-bold text-white hover:bg-primary-700">+ New account</a>
    </header>
    <div class="mb-6 flex gap-2 overflow-auto">
      <button class="rounded-full bg-surface-900 px-4 py-2 text-sm font-bold text-white">Active (3)</button>
      <button class="rounded-full border border-surface-200 bg-white px-4 py-2 text-sm font-semibold">Inactive (1)</button>
      <button class="rounded-full border border-surface-200 bg-white px-4 py-2 text-sm font-semibold">All</button>
    </div>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
      @for (account of accounts; track account.id; let index = $index) {
        <article class="rounded-3xl border border-surface-200 bg-white p-6 transition hover:-transurface-y-1 hover:shadow-lg">
          <div class="mb-8 flex items-start justify-between"><span class="grid h-12 w-12 place-items-center rounded-2xl bg-primary-50 text-xl font-bold text-primary-700">{{ account.name.charAt(0) }}</span><button class="text-xl text-surface-400">•••</button></div>
          <p class="text-sm font-medium text-surface-500">{{ account.name }}</p><strong class="mt-1 block text-3xl font-extrabold text-surface-900">{{ money(account.balance) }}</strong>
          <div class="mt-6 flex items-center justify-between border-t border-surface-100 pt-4"><span class="rounded-full bg-primary-50 px-3 py-1 text-xs font-bold text-primary-700">Active</span><a [routerLink]="['/dashboard/accounts', account.id]" class="text-sm font-bold text-primary-600">View details →</a></div>
        </article>
      }
    </div>
  `,
})
export class Accounts { readonly accounts = accounts; readonly money = money; }
