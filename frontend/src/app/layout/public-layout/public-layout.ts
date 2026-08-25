import { Component, ChangeDetectionStrategy } from '@angular/core';
import { Header } from './Components/header/header';
import { Hero } from './Components/hero/hero';
@Component({
  selector: 'app-public-layout',
  imports: [Header,Hero],
  templateUrl: './public-layout.html',
  changeDetection: ChangeDetectionStrategy.Eager,
})
export class PublicLayout {

}
