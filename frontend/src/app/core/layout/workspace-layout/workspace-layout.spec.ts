import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkspaceLayout } from './workspace-layout';

describe('WorkspaceLayout', () => {
  let component: WorkspaceLayout;
  let fixture: ComponentFixture<WorkspaceLayout>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [WorkspaceLayout],
    }).compileComponents();

    fixture = TestBed.createComponent(WorkspaceLayout);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
