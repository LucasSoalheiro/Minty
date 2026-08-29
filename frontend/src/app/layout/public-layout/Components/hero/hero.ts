import { Component } from '@angular/core';

// Benfits Icons
import { ChartLine } from '@primeicons/angular/chart-line';
import { Shield } from '@primeicons/angular/shield';
import { ArrowDownLeftAndArrowUpRightToCenter } from '@primeicons/angular/arrow-down-left-and-arrow-up-right-to-center';
import { Wallet } from '@primeicons/angular/wallet';
import { Lock } from '@primeicons/angular/lock';

// How to icons
import { ChartPie } from '@primeicons/angular/chart-pie';
import { Send } from '@primeicons/angular/send';
import { Bell } from '@primeicons/angular/bell';
import { BuildingColumns } from '@primeicons/angular/building-columns';

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

const HOWTO = [
  {
    icon: 'building-columns',
    step: '01',
    title: 'Connect your accounts',
    description: 'Link your bank in seconds, Minty reads your transactions automatically.',
  },
  {
    icon: 'chart-pie',
    step: '02',
    title: 'See it all in one place',
    description: 'Spending, income and balance, updated in real time.',
  },
  {
    icon: 'send',
    step: '03',
    title: 'Send money instantly',
    description: 'Transfer to anyone with a tap, no fees, no waiting.',
  },
  {
    icon: 'bell',
    step: '04',
    title: 'Stay ahead',
    description: 'Smart alerts before you overspend or miss a bill.',
  },
];

@Component({
  imports: [Shield, ArrowDownLeftAndArrowUpRightToCenter, Wallet, ChartLine, Lock, Send, ChartPie,Bell,BuildingColumns],
  selector: 'app-hero',
  styleUrl: './hero.scss',
  templateUrl: './hero.html',
})
export class Hero {
  benefit = BENEFITS;
  howt = HOWTO;
}
