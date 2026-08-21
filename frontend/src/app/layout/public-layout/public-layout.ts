import { Component, ChangeDetectionStrategy } from '@angular/core';

const LINKS = [
  {
    name: 'Inicio',
    href: '#',
  },
   {
    name: 'Inicio',
    href: '#',
  },
   {
    name: 'Inicio',
    href: '#',
  },
   {
    name: 'Inicio',
    href: '#',
  },
   {
    name: 'Inicio',
    href: '#',
  },
];

@Component({
  selector: 'app-public-layout',
  imports: [],
  templateUrl: './public-layout.html',
  changeDetection: ChangeDetectionStrategy.Eager,
})
export class PublicLayout {
  links = LINKS
}
