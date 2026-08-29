import { Component } from '@angular/core';
import { MobileButton } from './mobile-button';
import HeaderData from './data.json'
// header links
const LINKS = HeaderData;

@Component({
  imports: [MobileButton],
  selector: 'app-header',
  styleUrl: './header.scss',
  templateUrl: './header.html',
})
export class Header {
  links = LINKS;
}
