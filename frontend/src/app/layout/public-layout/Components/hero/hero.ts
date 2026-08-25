import { Component } from '@angular/core';
import { ChartPie } from '@primeicons/angular/chart-pie';
import { Shield } from '@primeicons/angular/shield';
import { ArrowDownLeftAndArrowUpRightToCenter } from '@primeicons/angular/arrow-down-left-and-arrow-up-right-to-center';

const BENEFITS = [
  {
    icon: 'chart-pie',
    title: 'Full control',
    description: 'Track your spending and income in real time.',
  },
  {
    icon: 'arrow-down-left-and-arrow-up-right-to-center',
    title: 'P2P transfers',
    description: 'Send and receive money instantly.',
  },
  {
    icon: 'shield',
    title: 'Security first',
    description: 'Your data protected end-to-end.',
  },
];

@Component({
  imports: [ChartPie,Shield,ArrowDownLeftAndArrowUpRightToCenter],
  selector: 'app-hero',
  styleUrl: './hero.scss',
  templateUrl: './hero.html',
})
export class Hero {

  benefit = BENEFITS;
}
