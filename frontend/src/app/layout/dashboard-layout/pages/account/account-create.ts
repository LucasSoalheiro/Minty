import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  standalone: true,
  imports: [RouterLink],
  template: `
    <div class="mx-auto max-w-2xl">
      <a routerLink="/dashboard/accounts" class="mb-6 inline-block font-semibold text-surface-500">← Back to accounts</a>
      <div class="rounded-3xl border border-surface-200 bg-white p-6 sm:p-9">
        <p class="font-semibold text-primary-600">New account</p><h1 class="text-3xl font-extrabold text-surface-900">Create a new account</h1><p class="mt-2 text-surface-500">Organize your money by purpose, everyday use, or future plans.</p>
        <form class="mt-8 space-y-6">
          <label class="block"><span class="mb-2 block text-sm font-bold text-surface-700">Account name</span><input placeholder="E.g. Emergency fund" class="w-full rounded-xl border border-surface-200 px-4 py-3 outline-none focus:border-primary-500" /></label>
          <label class="block"><span class="mb-2 block text-sm font-bold text-surface-700">Opening balance</span><div class="flex rounded-xl border border-surface-200 focus-within:border-primary-500"><span class="border-r border-surface-200 px-4 py-3 text-surface-500">R$</span><input placeholder="0.00" class="min-w-0 flex-1 rounded-r-xl px-4 py-3 outline-none" /></div><small class="mt-2 block text-surface-500">The opening balance can only be set when creating the account.</small></label>
          <div class="flex flex-col-reverse gap-3 border-t border-surface-100 pt-6 sm:flex-row sm:justify-end"><a routerLink="/dashboard/accounts" class="rounded-xl border border-surface-200 px-5 py-3 text-center font-bold">Cancel</a><button class="rounded-xl bg-primary-600 px-6 py-3 font-bold text-white">Create account</button></div>
        </form>
      </div>
    </div>
  `,
})
export class AccountCreate {}
