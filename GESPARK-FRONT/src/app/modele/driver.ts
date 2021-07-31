export class Driver {
  natureLicense: string;
  dateLicense: string;
  licenseFile: string;
  vehicle: string;
  firstname: string;
  lastname: string;
  address: string;
  phone: string;
  avatar: string;
  cin: string;

constructor(
    natureLicense: string,
    dateLicense: string,
    licenseFile: string,
    vehicle: string,
    firstname: string,
    lastname: string,
    address: string,
    phone: string,
    avatar: string,
    cin: string,
)
{
    this.natureLicense = natureLicense;
    this.dateLicense = dateLicense;
    this.licenseFile = licenseFile;
    this.vehicle = vehicle;
    this.firstname = firstname;
    this.lastname = lastname;
    this.address = address;
    this.phone = phone;
    this.avatar = avatar;
    this.cin = cin;
    
}
}

