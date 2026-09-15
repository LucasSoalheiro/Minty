import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { AvatarModule } from 'primeng/avatar';
import { SelectModule } from 'primeng/select';
import { SelectButtonModule } from 'primeng/selectbutton';
import { ToggleSwitchModule } from 'primeng/toggleswitch';
import { SidebarModule } from 'primeng/sidebar';
import { ButtonModule } from 'primeng/button';
import { LabelModule } from 'primeng/label';
import { SidebarCollapsible, SidebarSide, SidebarVariant } from 'primeng/types/sidebar';
import { Sidebar } from '@primeicons/angular/sidebar';
import { ChevronDown } from '@primeicons/angular/chevron-down';
import { EllipsisV } from '@primeicons/angular/ellipsis-v';
import { PIcon } from '@primeicons/angular/p-icon';
import { isActive } from '@angular/router';

interface NavItem {
  icon: string;
  label: string;
  isActive?: boolean;
  badge?: string;
  subItems?: { label: string; isActive?: boolean }[];
}


interface NavGroup {
  label: string;
  items: NavItem[];
}



@Component({
  selector: 'app-dashboard-menu',
  template: `
    <div class=" overflow-hidden ">
      <p-sidebar-layout class="min-h-full fixed ">
        @if (backdrop) {
          <p-sidebar-backdrop class="absolute!" />
        }
        <p-sidebar
          id="variants-demo"
          [variant]="variant"
          [collapsible]="collapsible"
          [side]="side"
          [overlay]="overlay"
          [openOnHover]="openOnHover"
        
        >
          <p-sidebar-spacer />
          <p-sidebar-aside>
            <!-- panel -->
            <p-sidebar-panel >
              <p-sidebar-header>
                <p-sidebar-menu>
                  <p-sidebar-menu-item>
                    <button pSidebarMenuButton class="px-1!">
                      <img src="/img/minty-icon.png" alt="icon" class="flex size-6" />
                      <span class="font-semibold text-sm">Minty</span>
                    </button>
                  </p-sidebar-menu-item>
                </p-sidebar-menu>
              </p-sidebar-header>
              <p-sidebar-content>
                @for (group of navGroups; track group.label) {
                  <p-sidebar-group>
                    <p-sidebar-group-label>{{ group.label }}</p-sidebar-group-label>
                    <p-sidebar-group-content>
                      <p-sidebar-menu>
                        @for (item of group.items; track item.label) {
                          <p-sidebar-menu-item
                            [collapsible]="!!item.subItems"
                            [defaultOpen]="hasActiveSub(item)"
                          >
                            <button pSidebarMenuButton [isActive]="!!item.isActive">
                              <svg [pIcon]="item.icon"></svg>
                              <span>{{ item.label }}</span>
                              @if (item.subItems) {
                                <svg data-p-icon="chevron-down" class="ml-auto"></svg>
                              }
                            </button>
                            @if (item.badge) {
                              <p-sidebar-menu-badge>{{ item.badge }}</p-sidebar-menu-badge>
                            }
                            @if (item.subItems) {
                              <p-sidebar-menu-sub>
                                @for (sub of item.subItems; track sub.label) {
                                  <p-sidebar-menu-sub-item>
                                    <button pSidebarMenuSubButton [isActive]="!!sub.isActive">
                                      <span>{{ sub.label }}</span>
                                    </button>
                                  </p-sidebar-menu-sub-item>
                                }
                              </p-sidebar-menu-sub>
                            } @else if (!item.badge) {
                              <button pSidebarMenuAction showOnHover>
                                <svg data-p-icon="ellipsis-v"></svg>
                              </button>
                            }
                          </p-sidebar-menu-item>
                        }
                      </p-sidebar-menu>
                    </p-sidebar-group-content>
                  </p-sidebar-group>
                }
              </p-sidebar-content>
              <p-sidebar-footer>
                <!-- User Accont -->
                <p-sidebar-menu>
                  <p-sidebar-menu-item>
                    <button pSidebarMenuButton class="p-1!">
                      <p-avatar label="JD" shape="circle" class="size-6 shrink-0 text-xs" />
                      <span>John Doe</span>
                    </button>
                  </p-sidebar-menu-item>
                </p-sidebar-menu>
              </p-sidebar-footer>
              <button pSidebarRail></button>
            </p-sidebar-panel>
          </p-sidebar-aside>
        </p-sidebar>

        <p-sidebar-main>
          <header
            class="flex  h-12 items-center gap-2  px-4"
          >
            <button
              pButton
              pSidebarTrigger
              target="variants-demo"
              severity="secondary"
              text
              size="small"
              class="flex md:hidden!"
            >
              <svg data-p-icon="sidebar"></svg>
            </button>
          </header>
          <ng-content  />
        </p-sidebar-main>
      </p-sidebar-layout>
    </div>
  `,
  standalone: true,
  imports: [
    AvatarModule,
    SelectModule,
    SelectButtonModule,
    ToggleSwitchModule,
    SidebarModule,
    ButtonModule,
    LabelModule,
    FormsModule,
    Sidebar,
    ChevronDown,
    EllipsisV,
    PIcon,
  ],
})
export class DashboardMenu {

  variant: SidebarVariant = 'floating';

  collapsible: SidebarCollapsible = 'icon';

  side: SidebarSide = 'left';

  overlay: boolean = true;

  openOnHover: boolean = true;

  backdrop: boolean = false;

  variantOptions = [
    { label: 'Sidebar', value: 'sidebar' },
    { label: 'Floating', value: 'floating' },
    { label: 'Inset', value: 'inset' },
  ];

  collapsibleOptions = [
    { label: 'Icon', value: 'icon' },
    { label: 'Offcanvas', value: 'offcanvas' },
    { label: 'None', value: 'none' },
  ];

  sideOptions = [
    { label: 'Left', value: 'left' },
    { label: 'Right', value: 'right' },
  ];

  navGroups: NavGroup[] = [
    {
      label: 'Navigation',
      items: [
        { icon: 'home', label: 'Home', isActive: true },
        { icon: 'inbox', label: 'Inbox', badge: '12' },
        { icon: 'search', label: 'Search' },
        { icon: 'bell', label: 'Notifications', badge: '3' },
      ],
    },
    // {
    //   label: 'Projects',
    //   items: [
    //     {
    //       icon: 'chart-bar',
    //       label: 'Analytics',
    //       subItems: [
    //         { label: 'Overview', isActive: true },
    //         { label: 'Reports' },
    //         { label: 'Real-time' },
    //       ],
    //     },
    //     { icon: 'users', label: 'Team' },
    //     { icon: 'calendar', label: 'Calendar' },
    //     {
    //       icon: 'folder',
    //       label: 'Documents',
    //       subItems: [{ label: 'Shared' }, { label: 'Private' }, { label: 'Archived' }],
    //     },
    //   ],
    // },
    // {
    //   label: 'Billing',
    //   items: [
    //     { icon: 'credit-card', label: 'Payments' },
    //     { icon: 'shopping-cart', label: 'Orders' },
    //     { icon: 'star', label: 'Subscriptions' },
    //   ],
    // },
  ];

  hasActiveSub(item: NavItem): boolean {
    return !!item.subItems?.some((s) => s.isActive);
  }
}
