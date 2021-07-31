import { Component, Input, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { AdminService } from 'src/app/services/admin.service';
import { AuthService } from 'src/app/services/auth.service';
import { DriverService } from '../../services/driver.service';
import { NotificationService } from '../notification/notification.service';
import { VehicleService } from '../../services/vehicle.service';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html',
  styleUrls: ['./admin.component.css']
})
export class AdminComponent implements OnInit 
{
    thumbnail: any;
    admins: any;
    role: any;
    username: any;
    token: any;
    notification: any;
    vehicles: any;
    drivers: any;
    constructor(
        private adminService: AdminService,
        private sanitizer: DomSanitizer,
        private authenService: AuthService,
        private notifService: NotificationService,
        private vehicleService: VehicleService,
        private driverService: DriverService

    ) {}

    ngOnInit() {
      this.getnotifLength();
      this.getVehicle();
      this.getDriver();
      
        
    }

    getRole()
    {
      this.token = localStorage.getItem('token');
      this.role = this.authenService.getRole(this.token);
      return this.role[0];
    }
    getUsername()
    {
      this.token = localStorage.getItem('token');
      return this.username = this.authenService.getUsername(this.token);
    }

    

    

    getnotifLength()
    {
       this.notifService.getAll().subscribe(
      (notification) => {
        this.notification = notification
      })
    }

    getVehicle()
    {
      this.vehicleService.getVehicles().subscribe(
        (vehicles) => {
          this.vehicles= vehicles;
        }

      )}

      getDriver()
      {
        this.driverService.getDriver().subscribe(
          (drivers) => {
            this.drivers = drivers;
          }
        )
      }
  

}
