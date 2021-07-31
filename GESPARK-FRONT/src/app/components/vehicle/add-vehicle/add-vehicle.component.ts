import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { DriverService } from 'src/app/services/driver.service';
import { UploardFileService } from 'src/app/services/uploard-file.service';
import { VehicleService } from 'src/app/services/vehicle.service';

@Component({
  selector: 'app-add-vehicle',
  templateUrl: './add-vehicle.component.html',
  styleUrls: ['./add-vehicle.component.css']
})
export class AddVehicleComponent implements OnInit {

   drivers: any[] =[];
   cartRegistrationReader: any;
    vehicleform: FormGroup;
    cartRegistration: any;
      
  



  constructor(
    private fb: FormBuilder,
    private driverService: DriverService,
    private vehicleService: VehicleService,
    private uploardFileService: UploardFileService,

     ) {}

      getDriverWithVehicle()
      {
        this.driverService.getDriverWithVehicle().subscribe(
        (drivers) => {
              
          for (const iterator of drivers) {
            if (iterator) {
              this.drivers.push(iterator.codeUser);
            }
          }
        }
      )}

    ngOnInit(): void {

      this.getDriverWithVehicle();

      this.vehicleform = this.fb.group({
        matricule: ['' , Validators.required],
        modele: ['' , Validators.required],
        mark: ['' , Validators.required],
        color: ['' , Validators.required],
        nature: ['' , Validators.required],
        numbPlace: ['' , Validators.required],
        location: ['' , Validators.required],
        driver: ['' , Validators.required],
        cartRegistration: ['' , Validators.required],
      });
    }

    addVehicle() {
      this.vehicleService.addVehicle(this.uploardFileService.convertObjetToFormData(this.vehicleform.value, this.cartRegistration)).subscribe(
        (res)=>{
          res;
        });      
    } 
     


    onFileSelect(event: any) {

      this.cartRegistration = event.target.files[0];
      console.log(this.cartRegistration);
      
      const reader = new FileReader();
            reader.readAsDataURL(event.target.files[0]);
            
            reader.onload = () =>{
              this.cartRegistrationReader = reader.result;

              //show image
		          const uploardFile: any = document.getElementById('uploardFile');
              uploardFile.innerHTML = "";
              var img: any = new Image();
              img.src = reader.result;
              uploardFile.appendChild(img);
              img.className+='uploardFileImage';

            }


      this.vehicleform.get('cartRegistration')?.setValue(event.target.files[0]);

    }    
}
