import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute, ActivatedRouteSnapshot, Router, RouterState, RouterStateSnapshot } from '@angular/router';
import { InsuranceService } from 'src/app/services/insurance.service';

@Component({
  selector: 'app-detail-insurance',
  templateUrl: './detail-insurance.component.html',
  styleUrls: ['./detail-insurance.component.css']
})
export class DetailInsuranceComponent implements OnInit {

  codeVehicle: any;
  insurances: any;
  invoice: any;
  constructor(
    private insuranceService: InsuranceService,
    private sanitizer: DomSanitizer,
    private router: Router,
    ) {
          const state: RouterState = router.routerState;
          const snapshot: RouterStateSnapshot = state.snapshot;
          this.codeVehicle = snapshot.url.split('/');
          this.codeVehicle = this.codeVehicle[3];
     }

  ngOnInit(): void {
    this.codeVehicle;    
    this.getInsuranceByVehicle();
  }

  getInsuranceByVehicle()
  {
    this.insuranceService.getInsuranceByVehicle(this.codeVehicle).subscribe(
      insurance => {    
        this.insurances = insurance;
          if (this.insurances.cartRegistration) {
            let objectURL = 'data:image/jpeg;base64,' + this.insurances.invoice;
            this.invoice = this.sanitizer.bypassSecurityTrustUrl(objectURL);
            this.insurances.invoice = this.invoice;
          }
      }
    )
  }
}
