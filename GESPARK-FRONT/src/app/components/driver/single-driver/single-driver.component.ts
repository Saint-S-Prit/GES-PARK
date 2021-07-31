import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute } from '@angular/router';
import { DriverService } from '../../../services/driver.service';

@Component({
  selector: 'app-single-driver',
  templateUrl: './single-driver.component.html',
  styleUrls: ['./single-driver.component.css']
})
export class SingleDriverComponent implements OnInit {
  driver: any;
  codeUser: any;
  avatar: any;
  licenseFile: any;
  cartRegistration: any;

  constructor(
    private driverService: DriverService,
    private route: ActivatedRoute,
    private sanitizer: DomSanitizer,
    ) { }

  ngOnInit(): void {
    this.codeUser = this.route.snapshot.params.codeUser;
    this.getDriverByUserCode();
  }



  getDriverByUserCode()
  {
    this.driverService.getDriverByUserCode(this.codeUser).subscribe(
      driver => {
        this.driver = driver;

          if (this.driver.avatar) {
            let objectURL = 'data:image/jpeg;base64,' + this.driver.avatar;
            this.avatar = this.sanitizer.bypassSecurityTrustUrl(objectURL);
            this.driver.avatar = this.avatar;
          }
          if (this.driver.licenseFile) {
            let objectURL = 'data:image/jplicenseFileeg;base64,' + this.driver.vehicle.licenseFile;
            this.licenseFile = this.sanitizer.bypassSecurityTrustUrl(objectURL);
            this.driver.licenseFile = this.licenseFile;
          }
          if (this.driver.vehicle.cartRegistration) {
            let objectURL = 'data:image/jpeg;base64,' + this.driver.vehicle.cartRegistration;
            this.cartRegistration = this.sanitizer.bypassSecurityTrustUrl(objectURL);
            this.driver.vehicle.cartRegistration = this.cartRegistration;
          }
      }
    )
  }

}
