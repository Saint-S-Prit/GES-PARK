export class Admin {
    firstname : string;
    lastname : string;
    email : string;
    password : string;
    address : string;
    phone : string;
    avatar : string;
    cin : string;
    codeUser: string;

    constructor(
        
        firstname : string,
        lastname : string,
        email : string,
        password : string,
        address : string,
        phone : string,
        avatar : string,
        cin : string,
        codeUser: string
    )
    {
        this.firstname = firstname;
        this.lastname = lastname;
        this.email   = email;
        this.password = password;
        this.address = address;
        this.phone = phone;
        this.avatar = avatar;
        this.cin = cin;
        this.codeUser = codeUser;
    }
}
