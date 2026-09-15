import { Component } from '@angular/core';

@Component({
  standalone: true,
  template: `
    <header class="mb-8"><p class="font-semibold text-primary-600">History</p><h1 class="text-3xl font-extrabold text-surface-900">Archived</h1><p class="mt-2 max-w-2xl text-surface-500">Deactivated accounts and categories remain available to preserve your transaction history.</p></header>
    <div class="mb-5 flex gap-2"><button class="rounded-full bg-surface-900 px-4 py-2 text-sm font-bold text-white">Accounts</button><button class="rounded-full border border-surface-200 bg-white px-4 py-2 text-sm font-bold">Categories</button></div>
    <section class="rounded-3xl border border-surface-200 bg-white p-6"><div class="flex flex-col gap-4 sm:flex-row sm:items-center"><span class="grid h-12 w-12 place-items-center rounded-2xl bg-surface-100 text-xl">□</span><span class="flex-1"><strong class="block text-surface-800">Old account</strong><small class="text-surface-500">Deactivated · historical balance R$0.00</small></span><span class="rounded-full bg-surface-100 px-3 py-1 text-xs font-bold text-surface-500">INACTIVE</span><button class="font-bold text-primary-600">View history</button></div></section>
    <div class="mt-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800"><strong>Read-only.</strong> Inactive accounts cannot be reactivated or used for new transactions.</div>
  `,
})
export class Archived {}
