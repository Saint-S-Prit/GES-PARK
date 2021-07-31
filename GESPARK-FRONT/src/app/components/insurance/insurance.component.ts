import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { InsuranceService } from 'src/app/services/insurance.service';

@Component({
  selector: 'app-insurance',
  templateUrl: './insurance.component.html',
  styleUrls: ['./insurance.component.css']
})
export class InsuranceComponent implements OnInit {

  constructor(
    private insuranceService: InsuranceService,
    private sanitizer: DomSanitizer,
    ) {}

  insurances: any;
  invoice: any;

  ngOnInit(): void {
    this.getInsurance();
  }

  getInsurance()
  {
    this.insuranceService.getInsurance().subscribe(
      (insurances) => {
          this.insurances = insurances;
           for (let index of this.insurances) {
              if (index.invoice) {
                let objectURL = 'data:image/jpeg;base64,' + index.invoice;
                this.invoice = this.sanitizer.bypassSecurityTrustUrl(objectURL);
                index.invoice = this.invoice;
              }
            }
      }
    )
  }
}
