import { Component, ChangeDetectionStrategy } from '@angular/core';
import { Header } from './Components/header/header';
@Component({
  selector: 'app-public-layout',
  imports: [Header],
  templateUrl: './public-layout.html',
  changeDetection: ChangeDetectionStrategy.Eager,
})
export class PublicLayout {

}
