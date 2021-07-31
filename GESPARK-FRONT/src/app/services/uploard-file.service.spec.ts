import { TestBed } from '@angular/core/testing';

import { UploardFileService } from './uploard-file.service';

describe('UploardFileService', () => {
  let service: UploardFileService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(UploardFileService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
