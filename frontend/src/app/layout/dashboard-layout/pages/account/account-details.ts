import { Component, inject } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { accounts, money, transactions } from '../../mock/mock-data';

@Component({
  standalone: true,
  imports: [RouterLink],
  template: `
    <a routerLink="/dashboard/accounts" class="mb-5 inline-block font-semibold text-slate-500">← All accounts</a>
    <section class="rounded-3xl bg-gradient-to-br from-slate-900 to-slate-800 p-6 text-white sm:p-8">
      <div class="flex flex-col gap-8 sm:flex-row sm:items-start sm:justify-between">
        <div><span class="rounded-full bg-white/10 px-3 py-1 text-xs font-bold text-emerald-200">ACTIVE</span><h1 class="mt-4 text-2xl font-bold text-white!">{{ account.name }}</h1><p class="mt-1 text-sm text-slate-400">ID: {{ account.id }} <button class="ml-2 text-emerald-300">Copy</button></p></div>
        <div class="sm:text-right"><p class="text-sm text-slate-400">Available balance</p><strong class="mt-1 block text-4xl font-extrabold text-white!">{{ money(account.balance) }}</strong></div>
      </div>
      <div class="mt-8 grid grid-cols-2 gap-3 sm:flex">
        <button class="rounded-xl bg-white px-5 py-3 font-bold text-slate-900">↓ Deposit</button><button class="rounded-xl bg-white/10 px-5 py-3 font-bold text-white!">↑ Withdraw</button><a routerLink="/dashboard/transfer" class="rounded-xl bg-emerald-500 px-5 py-3 text-center font-bold text-white!">↗ Transfer</a><button class="rounded-xl bg-white/10 px-5 py-3 font-bold text-white!">Edit</button>
      </div>
    </section>
    <section class="mt-6 rounded-3xl border border-slate-200 bg-white p-5 sm:p-6">
      <div class="mb-4 flex items-center justify-between"><div><h2 class="text-xl font-bold">Latest transactions</h2><p class="text-sm text-slate-500">Account activity</p></div><a [routerLink]="['/dashboard/accounts', account.id, 'transactions']" class="text-sm font-bold text-emerald-600">Full statement</a></div>
      <div class="divide-y divide-slate-100">@for (item of transactions.slice(0, 4); track item.id) {<div class="flex items-center gap-4 py-4"><span class="grid h-10 w-10 place-items-center rounded-full bg-slate-100">{{ item.type === 'INFLOW' ? '↓' : '↑' }}</span><span class="min-w-0 flex-1"><strong class="block truncate text-slate-800">{{ item.description }}</strong><small class="text-slate-500">{{ item.category }} · {{ item.date }}</small></span><strong [class]="item.type === 'INFLOW' ? 'text-emerald-600' : 'text-slate-800'">{{ item.type === 'INFLOW' ? '+' : '-' }} {{ money(item.amount) }}</strong></div>}</div>
    </section>
    <button class="mt-6 rounded-xl border border-red-200 bg-white px-5 py-3 font-bold text-red-600">Deactivate this account</button>
  `,
})
export class AccountDetails {
  private readonly route = inject(ActivatedRoute);
  readonly money = money; readonly transactions = transactions;
  readonly account = accounts.find((item) => item.id === this.route.snapshot.paramMap.get('accountId')) ?? accounts[0];
}
