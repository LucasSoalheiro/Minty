import { Component } from '@angular/core';

const LINKS = [
   {
    name: 'Beneficios',
    href: '#',
  },
  {
    name: 'Sobre',
    href: '#',
  },
 
  {
    name: 'Como Usar',
    href: '#',
  },
  {
    name: 'Login',
    href: '#',
  },
];

@Component({
  imports: [],
  selector: 'app-header',
  styleUrl: './header.scss',
  templateUrl: './header.html',
})
export class Header {
  links = LINKS;
}
