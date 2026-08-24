import { Component } from '@angular/core';
import { MobileButton } from './button';
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
  imports: [MobileButton],
  selector: 'app-header',
  styleUrl: './header.scss',
  templateUrl: './header.html',
})
export class Header {
  links = LINKS;
}
