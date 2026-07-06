import { Component, ChangeDetectionStrategy } from '@angular/core';
import { Login } from "./components/login/login";
import { Register } from "./components/register/register";

@Component({
  selector: 'app-auth-layout',
  imports: [Login, Register],
  templateUrl: './auth-layout.html',
  changeDetection: ChangeDetectionStrategy.Eager,
  styleUrl: './auth-layout.scss',
})
export class AuthLayout {
  login: boolean = true;

  toggleAuth() {
    this.login = !this.login;
  }
}

