import { Component, Output, EventEmitter } from '@angular/core';
import { Form } from '../form/form';

@Component({
  selector: 'app-register',
  imports: [Form],
  templateUrl: './register.html',
  styleUrl: '../../auth-layout.scss',
})
export class Register {
  @Output() toggleLogin = new EventEmitter<void>();
}
