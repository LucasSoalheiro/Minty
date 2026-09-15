import { Component } from '@angular/core';

@Component({
  standalone: true,
  template: `
    <header class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"><div><p class="font-semibold text-emerald-600">Organization</p><h1 class="text-3xl font-extrabold text-slate-900">Categories</h1><p class="mt-2 text-slate-500">Organize your incoming and outgoing transactions your way.</p></div><button class="rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white!">+ New category</button></header>
    <div class="mb-5 flex gap-2"><button class="rounded-full bg-slate-900 px-4 py-2 text-sm font-bold text-white!">Active</button><button class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold">Inactive</button></div>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">@for (category of categories; track category.name) {<article class="rounded-2xl border border-slate-200 bg-white p-5"><div class="flex items-start justify-between"><span class="grid h-11 w-11 place-items-center rounded-xl text-xl" [class]="category.color">{{ category.icon }}</span><button class="text-slate-400">•••</button></div><h2 class="mt-5 font-bold text-slate-900">{{ category.name }}</h2><p class="mt-1 text-sm text-slate-500">{{ category.description }}</p><div class="mt-5 flex justify-between border-t border-slate-100 pt-4"><span class="text-xs font-bold text-emerald-600">ACTIVE</span><button class="text-sm font-bold text-slate-600">Edit</button></div></article>}</div>
  `,
})
export class Categories {
  readonly categories = [
    { name: 'Food & dining', description: 'Groceries, restaurants, and delivery', icon: '🍴', color: 'bg-orange-50' },
    { name: 'Income', description: 'Salary, refunds, and other income', icon: '↙', color: 'bg-emerald-50 text-emerald-700' },
    { name: 'Entertainment', description: 'Streaming, outings, and entertainment', icon: '♬', color: 'bg-violet-50' },
    { name: 'Transport', description: 'Fuel, ride sharing, and tickets', icon: '⌁', color: 'bg-sky-50' },
    { name: 'Transfers', description: 'Transfers between accounts', icon: '↗', color: 'bg-slate-100' },
  ];
}
