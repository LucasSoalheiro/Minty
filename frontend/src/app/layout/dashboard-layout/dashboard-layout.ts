// dashboard-layout.ts
import { Component, ChangeDetectionStrategy } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { DashboardMenu } from './components/dashboard-menu';

@Component({
  selector: 'app-dashboard-layout',
  imports: [DashboardMenu, RouterOutlet],
  templateUrl: './dashboard-layout.html',
  changeDetection: ChangeDetectionStrategy.Eager,
})
export class DashboardLayout {}
