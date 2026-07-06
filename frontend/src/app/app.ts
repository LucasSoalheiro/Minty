import { Component, signal, ChangeDetectionStrategy } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { AuthLayout } from "./core/layout/auth-layout/auth-layout";

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, AuthLayout],
  templateUrl: './app.html',
  changeDetection: ChangeDetectionStrategy.Eager,
})
export class App {
  protected readonly title = signal('frontend');
}
