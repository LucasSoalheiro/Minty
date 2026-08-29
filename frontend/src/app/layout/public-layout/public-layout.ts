import { Component, ChangeDetectionStrategy } from '@angular/core';
import { Header } from './Components/header/header';
import { Hero } from './Components/hero/hero';
import { Footer } from './Components/footer/footer';
@Component({
  selector: 'app-public-layout',
  imports: [Header,Footer,Hero],
  templateUrl: './public-layout.html',
  changeDetection: ChangeDetectionStrategy.Eager,
})
export class PublicLayout {

}
