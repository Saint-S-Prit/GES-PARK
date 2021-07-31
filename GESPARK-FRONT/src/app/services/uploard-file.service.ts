import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class UploardFileService {

  constructor() {}


  convertObjetToFormData(objet: any, cartRegistration?: any, licenseFile?: any, avatar?: any)
  {
    const formData = new FormData();

    let value: any;
    
    for(const [k, v] of Object.entries(objet))
    {      
      if (Array.isArray(v) || typeof v === 'object') {
        value =  JSON.stringify(v);
      }
      else
      {
        value = v;
      }

      formData.append(k, value);
    }

      if (cartRegistration) {
        formData.append('cartRegistration', cartRegistration);
      }

      if (licenseFile) {
        formData.append('licenseFile', licenseFile);
      }
      
      if (avatar) {
        formData.append('avatar', avatar);
      
      }

    return formData;
  }  
}