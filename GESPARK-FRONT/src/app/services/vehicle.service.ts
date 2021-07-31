import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { catchError, map, tap } from 'rxjs/operators'

// const httpOptions = {
//   headers: new HttpHeaders({ 
//     'Access-Control-Allow-Origin':'*',
   
//   })
// };

@Injectable({
  providedIn: 'root'
})
export class VehicleService {


   //get url api to d'environmrnt
    private urlServerApi = environment.serverApi;

  constructor(private http: HttpClient) { }

  addVehicle(data: any){
    return this.http.post(`${this.urlServerApi}vehicle`, data )

  }

  getVehicles(): Observable<any[]> {
      return this.http.get<any[]>(`${this.urlServerApi}vehicle`).pipe(
      map(apps => apps.filter(app => app.panne == null)));
  }

    getVehiclesPannes(): Observable<any[]> {
      return this.http.get<any[]>(`${this.urlServerApi}vehicle`).pipe(
      map(apps => apps.filter(app => app.panne != null)));
  }

    getVehicleWithDriver(): Observable<any[]> {
    return this.http.get<any[]>(`${this.urlServerApi}vehicle`).pipe(
      map(apps => apps.filter(app => app.driver == null)));
  }

  

  getVehicleByCodeVehicle(codeVehicle: any): Observable<any[]> {
    return this.http.get<any[]>(`${this.urlServerApi}vehicle/`+codeVehicle)       
  }
}
