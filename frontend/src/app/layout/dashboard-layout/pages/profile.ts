import { Component } from '@angular/core';

@Component({
  standalone: true,
  template: `
    <header class="mb-8"><p class="font-semibold text-emerald-600">Settings</p><h1 class="text-3xl font-extrabold text-slate-900">My profile</h1><p class="mt-2 text-slate-500">Manage your personal details and security.</p></header>
    <div class="grid gap-6 xl:grid-cols-2">
      <form class="rounded-3xl border border-slate-200 bg-white p-6"><div class="mb-6 flex items-center gap-4"><span class="grid h-16 w-16 place-items-center rounded-full bg-emerald-100 text-xl font-bold text-emerald-700">GS</span><div><h2 class="text-xl font-bold">Personal details</h2><p class="text-sm text-slate-500">Your account information.</p></div></div><div class="space-y-4"><label class="block"><span class="mb-2 block text-sm font-bold">Name</span><input value="Gustavo Silva" class="w-full rounded-xl border border-slate-200 px-4 py-3" /></label><label class="block"><span class="mb-2 block text-sm font-bold">Email</span><input value="gustavo@email.com" class="w-full rounded-xl border border-slate-200 px-4 py-3" /></label><button class="rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white!">Save changes</button></div></form>
      <form class="rounded-3xl border border-slate-200 bg-white p-6"><h2 class="text-xl font-bold">Security</h2><p class="mb-6 text-sm text-slate-500">Update your sign-in password.</p><div class="space-y-4"><label class="block"><span class="mb-2 block text-sm font-bold">Current password</span><input type="password" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 px-4 py-3" /></label><label class="block"><span class="mb-2 block text-sm font-bold">New password</span><input type="password" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 px-4 py-3" /></label><label class="block"><span class="mb-2 block text-sm font-bold">Confirm new password</span><input type="password" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 px-4 py-3" /></label><button class="rounded-xl bg-slate-900 px-5 py-3 font-bold text-white!">Change password</button></div></form>
    </div>
    <section class="mt-6 flex flex-col gap-4 rounded-3xl border border-red-100 bg-white p-6 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="font-bold text-slate-900">Sign out</h2><p class="text-sm text-slate-500">You will need to sign in again to access your dashboard.</p></div><a href="/auth-login" class="rounded-xl border border-red-200 px-5 py-3 text-center font-bold text-red-600">Sign out</a></section>
  `,
})
export class Profile {}
