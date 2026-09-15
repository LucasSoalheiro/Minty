import { Component } from '@angular/core';

@Component({
  standalone: true,
  template: `
    <div class="mx-auto max-w-3xl"><header class="mb-7"><p class="font-semibold text-emerald-600">Transaction</p><h1 class="text-3xl font-extrabold text-slate-900">New transfer</h1><p class="mt-2 text-slate-500">Send money between your accounts or to someone else.</p></header>
    <div class="mb-6 flex items-center gap-2">@for (step of [1,2,3]; track step) {<span class="grid h-8 w-8 place-items-center rounded-full text-sm font-bold" [class]="step === 1 ? 'bg-emerald-600 text-white!' : 'bg-slate-200 text-slate-500'">{{ step }}</span>@if (step < 3) {<span class="h-0.5 flex-1 bg-slate-200"></span>}}</div>
    <form class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 sm:p-8">
      <fieldset><legend class="mb-3 font-bold text-slate-800">Recipient type</legend><div class="grid grid-cols-2 gap-3"><label class="rounded-xl border-2 border-emerald-500 bg-emerald-50 p-4"><input checked type="radio" name="target" class="mr-2" /> My account</label><label class="rounded-xl border border-slate-200 p-4"><input type="radio" name="target" class="mr-2" /> Someone else</label></div></fieldset>
      <label class="block"><span class="mb-2 block text-sm font-bold">From account</span><select class="w-full rounded-xl border border-slate-200 px-4 py-3"><option>Main wallet — R$4,780.20</option><option>Savings — R$8,000.00</option></select></label>
      <label class="block"><span class="mb-2 block text-sm font-bold">To account</span><select class="w-full rounded-xl border border-slate-200 px-4 py-3"><option>Select an account</option><option>Savings</option><option>Travel</option></select><small class="mt-2 block text-slate-500">Use the recipient’s account ID when sending to someone else.</small></label>
      <div class="grid gap-4 sm:grid-cols-2"><label><span class="mb-2 block text-sm font-bold">Amount</span><input placeholder="R$0.00" class="w-full rounded-xl border border-slate-200 px-4 py-3" /></label><label><span class="mb-2 block text-sm font-bold">Category</span><select class="w-full rounded-xl border border-slate-200 px-4 py-3"><option>Transfers</option><option>Savings</option></select></label></div>
      <label class="block"><span class="mb-2 block text-sm font-bold">Description (optional)</span><input placeholder="What is this transfer for?" class="w-full rounded-xl border border-slate-200 px-4 py-3" /></label>
      <button class="w-full rounded-xl bg-emerald-600 px-6 py-3.5 font-bold text-white!">Continue to review</button>
    </form></div>
  `,
})
export class Transfer {}
