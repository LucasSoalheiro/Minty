import { Component, Output, EventEmitter } from '@angular/core';
import { Form } from "../form/form";

@Component({
  selector: 'app-login',
  imports: [Form],
  templateUrl: './login.html',
})
export class Login {
  @Output() toggleRegister = new EventEmitter<void>();
}

