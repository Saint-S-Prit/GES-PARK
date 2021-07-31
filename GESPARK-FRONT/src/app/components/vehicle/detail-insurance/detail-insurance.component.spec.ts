import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailInsuranceComponent } from './detail-insurance.component';

describe('DetailInsuranceComponent', () => {
  let component: DetailInsuranceComponent;
  let fixture: ComponentFixture<DetailInsuranceComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DetailInsuranceComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DetailInsuranceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
