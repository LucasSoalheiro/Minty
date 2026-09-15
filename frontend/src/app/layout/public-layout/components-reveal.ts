import { afterNextRender, DestroyRef, Directive, ElementRef, inject } from '@angular/core';

@Directive({ selector: '[appReveal]' })
export class Reveal {
  private readonly element = inject<ElementRef<HTMLElement>>(ElementRef);
  private readonly destroyRef = inject(DestroyRef);

  constructor() {
    afterNextRender(() => {
      if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;
      const element = this.element.nativeElement;
      element.classList.add('reveal-pending');
      const observer = new IntersectionObserver((entries) => {
        if (entries.some((entry) => entry.isIntersecting)) {
          element.classList.remove('reveal-pending');
          element.classList.add('reveal-visible');
          observer.disconnect();
        }
      }, { threshold: 0, rootMargin: '0px 0px -32px 0px' });
      observer.observe(element);
      this.destroyRef.onDestroy(() => observer.disconnect());
    });
  }
}
