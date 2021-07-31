import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { VehicleService } from '../../services/vehicle.service';

@Component({
  selector: 'app-vehicle',
  templateUrl: './vehicle.component.html',
  styleUrls: ['./vehicle.component.css']
})
export class VehicleComponent implements OnInit {

  vehicles: any;
  thumbnail: any;
  constructor(
    private vehicleService: VehicleService,
    private sanitizer: DomSanitizer,
    ) { }

  ngOnInit(): void {
    this.getVehicles();
  }



   getVehicles()
  {
    this.vehicleService.getVehicles().subscribe(
      (vehicles) => {
        
        this.vehicles = vehicles;
        for (let index of this.vehicles) {
              if (index.cartRegistration) {
                let objectURL = 'data:image/jpeg;base64,' + index.cartRegistration;
                this.thumbnail = this.sanitizer.bypassSecurityTrustUrl(objectURL);
                index.avatar = this.thumbnail;
              }
            }
      }
    )
  }

}


