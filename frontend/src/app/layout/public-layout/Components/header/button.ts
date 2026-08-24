import { Component } from '@angular/core';
import { DrawerModule } from 'primeng/drawer';
import { ButtonModule } from 'primeng/button';
import { Bars } from '@primeicons/angular/bars';
import { AngleDoubleRight } from '@primeicons/angular/angle-double-right';
// header links
const LINKS = [
  {
    name: 'Beneficios',
    href: '#',
  },
  {
    name: 'Como Usar',
    href: '#',
  },
  {
    name: 'Sobre',
    href: '#',
  },
  {
    name: 'Contato',
    href: '#',
  },
];

@Component({
  template: `
    <div class="flex justify-center">
      <p-drawer
        [(visible)]="visibleTop"
        /* header="Minty" */
        position="top"
        styleClass="w-full! md:w-80! h-auto!    "
      >
        <ng-template #header>
          <img src="/img/minty-logo.png" alt="Minty" class="h-15 " />
        </ng-template>

        <div class="flex flex-col gap-6 pt-2  px-2">
          <!-- Header Links -->
          <nav>
            <ul class="flex flex-col gap-2 p-0 m-0 list-none">
              @for (link of links; track link.name) {
                <li>
                  <a
                    [href]="link.href"
                    (click)="visibleTop = false"
                    class="flex items-center justify-between w-full px-4 py-3 text-base font-medium text-slate-600! hover:bg-emerald-100 rounded-xl transition-all duration-200 group"
                  >
                    <span>{{ link.name }}</span>
                    <svg data-p-icon="angle-double-right"></svg>
                  </a>
                </li>
              }
            </ul>
          </nav>

          <!-- Login button -->
          <div class="pt-2 border-t border-black">
            <a href="/auth-login" class="">
              <button
                type="button"
                class="w-full h-12 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-xl flex items-center justify-center gap-2 shadow-md hover:shadow-emerald-500/20 transition-all duration-200 cursor-pointer active:scale-[0.98]"
              >
                Entrar
              </button>
            </a>
          </div>
        </div>
      </p-drawer>

      <!-- Action Button -->
      <button
        type="button"
        class="bg-emerald-500! hover:bg-emerald-600! border-emerald-500!"
        pButton
        iconOnly
        (click)="visibleTop = true"
      >
        <svg data-p-icon="bars"></svg>
      </button>
    </div>
  `,
  standalone: true,
  imports: [DrawerModule, ButtonModule, Bars, AngleDoubleRight],
  selector: 'mobile-menu',
})
export class MobileButton {
  visibleTop: boolean = false;
  links = LINKS;
}
