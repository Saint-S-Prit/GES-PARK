import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable} from 'rxjs';
import { environment } from 'src/environments/environment';
import { map } from 'rxjs/operators'
import { Driver } from '../modele/driver';


@Injectable({
  providedIn: 'root'
})
export class DriverService {

    //get url api to d'environmrnt
    private urlServerApi = environment.serverApi;

    constructor(
        private http: HttpClient,
    ) {}

        addDriver(data: any){
          return this.http.post(`${this.urlServerApi}driver`, data);
        }

        getDriver(): Observable<any[]> {
          return this.http.get<any[]>(`${this.urlServerApi}driver`).pipe(
            map(apps => apps.filter(app => app.status == false)));    
        }

        getDriverWithVehicle(): Observable<any[]> {
          return this.http.get<any[]>(`${this.urlServerApi}driver`).pipe(
            map(apps => apps.filter(app => app.vehicle == null && app.status == false)));     
        }

        getDriverByUserCode(codeUser: any): Observable<any[]> {
          return this.http.get<any[]>(`${this.urlServerApi}driver/`+codeUser)
        }
}
