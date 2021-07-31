import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { Router, RouterState, RouterStateSnapshot } from '@angular/router';
import { VehicleService } from 'src/app/services/vehicle.service';

@Component({
  selector: 'app-detail-driver',
  templateUrl: './detail-driver.component.html',
  styleUrls: ['./detail-driver.component.css']
})
export class DetailDriverComponent implements OnInit {

  codeVehicle: any;
  vehicle: any;
  cartRegistration: any;
  driverAvatar: any;
  notExist: any;
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
    this.getdetailDriverByVehicle();

  }


  getdetailDriverByVehicle()
  {
    this.vehicleService.getVehicleByCodeVehicle(this.codeVehicle).subscribe(
      vehicle => {   
        this.notExist = "Pas de contenu"; 
        this.vehicle = vehicle;        
          if (this.vehicle.driver) {
            let objectURL = 'data:image/jpeg;base64,' + this.vehicle.driver.avatar;
            this.driverAvatar = this.sanitizer.bypassSecurityTrustUrl(objectURL);
            this.vehicle.driver.avatar = this.driverAvatar;
          }
      }
    )
  }
}
