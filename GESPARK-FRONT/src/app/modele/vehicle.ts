export interface Vehicle {
    matricule : string;
    modele : string;
    mark : string;
    color : string;
    numbPlace : string;
    location : string,
    cartRegistration : string,
    driver : string,
    nature : string
}


export class Vehicle {
    matricule : string;
    modele : string;
    mark : string;
    color : string;
    numbPlace : string;
    location : string;
    cartRegistration : string;
    driver : string;
    nature: string

    constructor(
        
    matricule : string,
    modele : string,
    mark : string,
    color : string,
    numbPlace : string,
    location : string,
    cartRegistration : string,
    driver : string,
    nature : string
    )
    {
        this.matricule = matricule;
        this.modele = modele;
        this.mark   = mark;
        this.color = color;
        this.numbPlace = numbPlace;
        this.location = location;
        this.cartRegistration = cartRegistration;
        this.driver = driver;
        this.nature = nature;
    }
}