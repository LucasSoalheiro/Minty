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
    href: '#',
  },
  {
    name: 'How To Use',
    href: '#',
  },
  {
    name: 'About',
    href: '#',
  },
  {
    name: 'Contact',
    href: '#',
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
