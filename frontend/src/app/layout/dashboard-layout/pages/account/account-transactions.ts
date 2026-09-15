import { Component, inject } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { money, transactions } from '../../mock/mock-data';

@Component({
  standalone: true,
  imports: [RouterLink],
  template: `
    <a [routerLink]="['/dashboard/accounts', accountId]" class="mb-5 inline-block font-semibold text-slate-500">← Back to account</a>
    <header class="mb-7"><p class="font-semibold text-emerald-600">Main wallet</p><h1 class="text-3xl font-extrabold text-slate-900">Statement</h1><p class="mt-2 text-slate-500">Track all transactions for this account.</p></header>
    <div class="mb-5 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 sm:grid-cols-3">
      <select class="rounded-xl border border-slate-200 px-4 py-3"><option>All statuses</option><option>Completed</option><option>Pending</option><option>Cancelled</option></select>
      <select class="rounded-xl border border-slate-200 px-4 py-3"><option>Incoming and outgoing</option><option>Incoming</option><option>Outgoing</option></select>
      <select class="rounded-xl border border-slate-200 px-4 py-3"><option>All categories</option><option>Food & dining</option><option>Entertainment</option></select>
    </div>
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white">
      <div class="divide-y divide-slate-100">@for (item of transactions; track item.id) {<article class="flex flex-wrap items-center gap-4 p-5"><span class="grid h-11 w-11 place-items-center rounded-full" [class]="item.type === 'INFLOW' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100'">{{ item.type === 'INFLOW' ? '↓' : '↑' }}</span><span class="min-w-0 flex-1"><strong class="block truncate text-slate-800">{{ item.description }}</strong><small class="text-slate-500">{{ item.category }} · {{ item.date }}</small></span><span class="rounded-full px-3 py-1 text-xs font-bold" [class]="statusClass(item.status)">{{ label(item.status) }}</span><strong class="w-28 text-right" [class]="item.type === 'INFLOW' ? 'text-emerald-600' : 'text-slate-800'">{{ item.type === 'INFLOW' ? '+' : '-' }} {{ money(item.amount) }}</strong>@if (item.status === 'PENDING') {<button disabled title="Not available in this preview" class="w-full text-right text-xs font-bold text-red-400 sm:w-auto">Cancel*</button>}</article>}</div>
    </div>
    <p class="mt-3 text-xs text-slate-400">* Transaction cancellation is not available in this preview.</p>
  `,
})
export class AccountTransactions {
  private readonly route = inject(ActivatedRoute);
  readonly money = money; readonly transactions = transactions; readonly accountId = this.route.snapshot.paramMap.get('accountId');
  label(status: string) { return ({ DONE: 'Completed', PENDING: 'Pending', CANCELLED: 'Cancelled' } as Record<string, string>)[status]; }
  statusClass(status: string) { return status === 'DONE' ? 'bg-emerald-50 text-emerald-700' : status === 'PENDING' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-500'; }
}
