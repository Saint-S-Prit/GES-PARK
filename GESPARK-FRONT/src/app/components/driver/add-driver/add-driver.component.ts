import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { DriverService } from 'src/app/services/driver.service';
import { UploardFileService } from 'src/app/services/uploard-file.service';
import { VehicleService } from 'src/app/services/vehicle.service';

@Component({
  selector: 'app-add-driver',
  templateUrl: './add-driver.component.html',
  styleUrls: ['./add-driver.component.css']
})
export class AddDriverComponent implements OnInit {

   vehicles: any[] =[];
   driverform: FormGroup;
   avatar: any;
   licenseFile: any;
   licenseFileReader: any;
   permis: string[]  = ['lourd', 'leger','myen'];

  constructor(
    private fb: FormBuilder,
    private vehicleService: VehicleService,
    private driverService: DriverService,
    private uploardFileService: UploardFileService,
    ) { 
    
  }

      getVehicleWithDriver()
      {
        this.vehicleService.getVehicleWithDriver().subscribe(
        (vehicles) => {

          for (const iterator of vehicles) {
            if (iterator) {
              this.vehicles.push(iterator.codeVehicle);
            }
          }
        }
      )
      }

  ngOnInit(): void {

        this.getVehicleWithDriver();

        this.driverform = this.fb.group({
        firstname: ['' , Validators.required],
        lastname: ['' , Validators.required],
        email: ['' , Validators.required],
        phone: ['' , Validators.required],
        address: ['' , Validators.required],
        dateLicense: ['' , Validators.required],
        natureLicense: ['' , Validators.required],
        vehicle: ['' , Validators.required],
        cin: ['' , Validators.required],
        licenseFile: ['' , Validators.required],
        avatar: ['' , Validators.required],
      });
  }


    addDriver() {
    this.driverService.addDriver(this.uploardFileService.convertObjetToFormData(this.driverform.value, null, this.licenseFile, this.avatar)).subscribe(
      (res)=>{
        res;
      });      
    } 





      onFilelicense(event: any) {

      this.licenseFile = event.target.files[0];
      console.log(this.licenseFile);


      this.driverform.get('licenseFile')?.setValue(event.target.files[0]);






        if (!event.target.files[0] && event.target.files[0].length === 0) {
          return;
        }
        const mimeType = event.target.files[0].type;
        if (mimeType.match(/image\/*/) == null) {
          return;
        }
        this.licenseFile = event.target.files[0];
          const readerlicense = new FileReader();
          readerlicense.readAsDataURL(event.target.files[0]);
          readerlicense.onload = () =>{
            this.licenseFileReader = readerlicense.result;
            }
      } 

    onFileAvatar(event: any) {

      this.avatar = event.target.files[0];
      console.log(this.avatar);

      this.driverform.get('avatar')?.setValue(event.target.files[0]);

      

      // if (!event.target.files[0] && event.target.files[0].length === 0) {
      //   return;
      // }
      // const mimeType = event.target.files[0].type;
      // if (mimeType.match(/image\/*/) == null) {
      //   return;
      // }
      //   this.avatar = event.target.files[0];
      //   const readerAvatar = new FileReader();
      //       readerAvatar.readAsDataURL(event.target.files[0]);
      //       readerAvatar.onload = () =>{
      //         this.avatarReader = readerAvatar.result;
      //         // //show image
		  //         // const uploardFile: any = document.getElementById('uploardFile');
      //         // uploardFile.innerHTML = "";
      //         // var img: any = new Image();
      //         // img.src = readerAvatar.result;
      //         // uploardFile.appendChild(img);
      //         // img.className+='uploardFileImage';

      //       }
    }

}
