import { Component } from '@angular/core';
import { ChartLine } from '@primeicons/angular/chart-line';
import { Shield } from '@primeicons/angular/shield';
import { ArrowDownLeftAndArrowUpRightToCenter } from '@primeicons/angular/arrow-down-left-and-arrow-up-right-to-center';
import { Wallet } from '@primeicons/angular/wallet';
import { Lock } from '@primeicons/angular/lock';
const BENEFITS = [
  {
    icon: 'wallet',
    title: 'Full control',
    description: 'Track every expense and income the moment it happens.',
  },
  {
    icon: 'p2p',
    title: 'P2P transfers',
    description: 'Send and receive money instantly, no fees attached.',
  },
  {
    icon: 'shield',
    title: 'Security first',
    description: 'Your data protected end-to-end, always.',
  },
  {
    icon: 'chart-line',
    title: 'Spending insights',
    description: 'See exactly where your money goes each month.',
  },
  {
    icon: 'lock',
    title: 'Bank-grade encryption',
    description: 'Every transaction is encrypted from start to finish.',
  },
];

@Component({
  imports: [Shield, ArrowDownLeftAndArrowUpRightToCenter, Wallet, ChartLine,Lock],
  selector: 'app-hero',
  styleUrl: './hero.scss',
  templateUrl: './hero.html',
})
export class Hero {
  benefit = BENEFITS;
}
