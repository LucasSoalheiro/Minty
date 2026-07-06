import { Component, Output, EventEmitter } from '@angular/core';
import { Form } from "../form/form";

@Component({
  selector: 'app-register',
  imports: [Form],
  templateUrl: './register.html',
})
export class Register {
  @Output() toggleLogin = new EventEmitter<void>();
}

