import { Component } from '@angular/core';
import { MobileButton } from './mobile-button';
// header links
const LINKS = [
  {
    name: 'Home',
    href: '#',
  },
  {
    name: 'Benefits',
    href: '#benefits',
  },
  {
    name: 'How To Use',
    href: '#how',
  },
  {
    name: 'About',
    href: '#about',
  },
  {
    name: 'Contact',
    href: '#contact',
  },
];

@Component({
  imports: [MobileButton],
  selector: 'app-header',
  styleUrl: './header.scss',
  templateUrl: './header.html',
})
export class Header {
  links = LINKS;
}
