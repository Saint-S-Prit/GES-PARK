import { Component, OnInit } from '@angular/core';
import { VehicleService } from 'src/app/services/vehicle.service';

@Component({
  selector: 'app-panne',
  templateUrl: './panne.component.html',
  styleUrls: ['./panne.component.css']
})
export class PanneComponent implements OnInit {

  vehicles: any;
  constructor(private vehicleService: VehicleService) { }

  ngOnInit(): void {
    this.getVehiclesPannes();
  }
getVehiclesPannes()
{
  this.vehicleService.getVehiclesPannes().subscribe(
    (vehicles) => {
      this.vehicles = vehicles
    }
  )
}
}
