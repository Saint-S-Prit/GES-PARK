import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import jwt_decode from 'jwt-decode';


@Injectable({ providedIn: 'root' })
export class AuthService {

      private decodedToken: any;

    //get url api to d'environmrnt
    private urlServerApi = environment.serverApi;

    constructor(
        private http: HttpClient
    ) {}


    login(user:{}) {
        //post url login to generate token
        return this.http.post<any>(`${this.urlServerApi}login_check`, user )
    }

    logout() {
        this.http.post<any>(`${this.urlServerApi}/login_check`, {})
    }

      decodeToken(token: string): string {
        return jwt_decode(token);
    }
    getRole(token: string): string {
        this.decodedToken = jwt_decode(token);
        return  this.decodedToken.roles;
    }
    getUsername(token: string): string {
        this.decodedToken = jwt_decode(token);
        return  this.decodedToken.username;
    }
    getExpiryTime(token: string): any {
        this.decodedToken = jwt_decode(token);
        return  this.decodedToken.exp;
    }
    getDecodedToken(token: string): any {
        this.decodedToken = jwt_decode(token);
        return  this.decodedToken;
    }

    getToken()
    {
        // modify variable localStorage and add token
        return localStorage.getItem('token')
    }


}






    

