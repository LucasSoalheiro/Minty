import { Component, Output, EventEmitter } from '@angular/core';
import { Form } from "../form/form";

@Component({
  selector: 'app-login',
  imports: [Form],
  templateUrl: './login.html',
    styleUrl: '../../auth-layout.scss',
})
export class Login {
  @Output() toggleRegister = new EventEmitter<void>();
}

