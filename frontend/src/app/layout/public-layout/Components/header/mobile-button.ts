import { Component } from '@angular/core';
import { DrawerModule } from 'primeng/drawer';
import { ButtonModule } from 'primeng/button';
import { Bars } from '@primeicons/angular/bars';
import { AngleDoubleRight } from '@primeicons/angular/angle-double-right';
import HeaderData from './data.json';

// header links
const LINKS = HeaderData;

@Component({
  template: `
    <div class="flex justify-center">
      <p-drawer [(visible)]="visibleTop" position="top" styleClass="w-full! md:w-80! h-auto!    ">
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
                    (click)="handleLinkClick($event, link.href)"
                    class="flex items-center justify-between w-full px-4 py-3 text-base font-medium text-surface-600 border-l-3  border-primary-500 hover:bg-primary-100  transition-all duration-200 group"
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
                class="w-full h-12 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-lg flex items-center justify-center gap-2 shadow-md hover:shadow-primary-500/20 transition-all duration-200 cursor-pointer active:scale-[0.98]"
              >
                Sign In
              </button>
            </a>
          </div>
        </div>
      </p-drawer>

      <!-- Action Button -->
      <button
        type="button"
        class="bg-primary-500 hover:bg-primary-400 border-1 border-primary-400 h-10! w-10!"
        pButton
        iconOnly
        (click)="visibleTop = true"
      >
        <svg data-p-icon="bars" color="var(--p-primary-contrast-color)" size="30"></svg>
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

  handleLinkClick(event: MouseEvent, href: string) {
    event.preventDefault();
    this.visibleTop = false;

    setTimeout(() => {
      window.location.href = href;
    }, 150);
  }
}
