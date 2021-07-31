import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { DriverService } from 'src/app/services/driver.service';

@Component({
  selector: 'app-driver',
  templateUrl: './driver.component.html',
  styleUrls: ['./driver.component.css']
})
export class DriverComponent implements OnInit {

  drivers: any;
  avatar: any;
  licenseFile: any;

  constructor(
        private driverService: DriverService,
        private sanitizer: DomSanitizer,
    ) { }

  ngOnInit(): void {
    this.getDrivers();
  }


  getDrivers()
  {
    this.driverService.getDriver().subscribe(
      (drivers) => {
        this.drivers = drivers;
        for (let index of this.drivers) {
              if (index.avatar) {
                let objectURL = 'data:image/jpeg;base64,' + index.avatar;
                this.avatar = this.sanitizer.bypassSecurityTrustUrl(objectURL);
                index.avatar = this.avatar;
              }
              if (index.licenseFile) {
                let objectURL = 'data:image/jpeg;base64,' + index.licenseFile;
                this.licenseFile = this.sanitizer.bypassSecurityTrustUrl(objectURL);
                index.licenseFile = this.licenseFile;
              }
            }
      }
    )
  }

}
