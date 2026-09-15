// dashboard-layout.ts
import { Component, ChangeDetectionStrategy } from '@angular/core';
import { DashboardMenu } from './components/dashboard-menu';

@Component({
  selector: 'app-dashboard-layout',
  imports: [DashboardMenu],
  templateUrl: './dashboard-layout.html',
  changeDetection: ChangeDetectionStrategy.Eager,
})
export class DashboardLayout {}
