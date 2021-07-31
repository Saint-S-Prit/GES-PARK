import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class InsuranceService {



//get url api to d'environmrnt
private urlServerApi = environment.serverApi;

constructor(
    private http: HttpClient,
) {}


    getInsurance(): Observable<any[]> {
      return this.http.get<any[]>(`${this.urlServerApi}insurance`)
    }

    getInsuranceByVehicle(codeVehicle: any): Observable<any[]>
    {
      return this.http.get<any[]>(`${this.urlServerApi}vehicle/${codeVehicle}/insurance`)
    }
}
