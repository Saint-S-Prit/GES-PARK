import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute } from '@angular/router';
import { VehicleService } from 'src/app/services/vehicle.service';

@Component({
  selector: 'app-single-vehicle',
  templateUrl: './single-vehicle.component.html',
  styleUrls: ['./single-vehicle.component.css']
})
export class SingleVehicleComponent implements OnInit {

  vehicle: any;
  codeVehicle: any;
  avatar: any;
  licenseFile: any;
  cartRegistration: any;

  constructor(
    private vehicleService: VehicleService,
    private route: ActivatedRoute,
    private sanitizer: DomSanitizer,
    ) { }

  ngOnInit(): void {
    this.codeVehicle = this.route.snapshot.params.codeVehicle;    
    this.getVehicleByCodeVehicle();    
  }



  getVehicleByCodeVehicle()
  {
    this.vehicleService.getVehicleByCodeVehicle(this.codeVehicle).subscribe(
      vehicle => {            
        this.vehicle = vehicle;
          if (this.vehicle.cartRegistration) {
            let objectURL = 'data:image/jpeg;base64,' + this.vehicle.cartRegistration;
            this.cartRegistration = this.sanitizer.bypassSecurityTrustUrl(objectURL);
            this.vehicle.cartRegistration = this.cartRegistration;
          }
      }
    )
  }

}
