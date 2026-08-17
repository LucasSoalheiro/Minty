import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-form',
  imports: [],
  templateUrl: './form.html',
})
export class Form {
  @Input({ required: true }) title!: string;
  @Input() subtitle?: string;
}
