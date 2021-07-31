import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute, Router, RouterState, RouterStateSnapshot } from '@angular/router';
import { VehicleService } from 'src/app/services/vehicle.service';

@Component({
  selector: 'app-detail-file',
  templateUrl: './detail-file.component.html',
  styleUrls: ['./detail-file.component.css']
})
export class DetailFileComponent implements OnInit {

  codeVehicle: any;
  vehicle: any;
  cartRegistration: any;
  driverAvatar: any;
  constructor(
    private vehicleService: VehicleService,
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
    this.getcartRegistrationByVehicle();

    
  }

  getcartRegistrationByVehicle()
  {
    this.vehicleService.getVehicleByCodeVehicle(this.codeVehicle).subscribe(
      vehicle => {    
        this.vehicle = vehicle;
          if (this.vehicle.cartRegistration) {
            let objectURL = 'data:image/jpeg;base64,' + this.vehicle.cartRegistration;
            this.cartRegistration = this.sanitizer.bypassSecurityTrustUrl(objectURL);
            this.vehicle.cartRegistration = this.cartRegistration;
          }

          if (this.vehicle.driver.avatar) {
            let objectURL = 'data:image/jpeg;base64,' + this.vehicle.driver.avatar;
            this.driverAvatar = this.sanitizer.bypassSecurityTrustUrl(objectURL);
            this.vehicle.driver.avatar = this.driverAvatar;
          }
      }
    )
  }

}
