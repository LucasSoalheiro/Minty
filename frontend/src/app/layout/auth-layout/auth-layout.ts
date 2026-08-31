import { Component, ChangeDetectionStrategy } from '@angular/core';
import { Login } from './components/login/login';
import { Register } from './components/register/register';
import { MobileButton } from './components/mobile-button';
import HeaderData from "./components/data.json"

@Component({
  selector: 'app-auth-layout',
  imports: [Login, Register,MobileButton ],
  templateUrl: './auth-layout.html',
  changeDetection: ChangeDetectionStrategy.Eager,
  styleUrl: './auth-layout.scss',
})
export class AuthLayout {
  login: boolean = true;

  toggleAuth() {
    this.login = !this.login;
  }

  links = HeaderData;
}
