import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { Admin } from '../modele/admin';

@Injectable({
  providedIn: 'root'
})
export class AdminService {

  notification : any ;


    //get url api to d'environmrnt
    private urlServerApi = environment.serverApi;

    constructor(
        private http: HttpClient,
    ) {}


        getAdd(admin: Admin) {
          return this.http.post<Admin>(`${this.urlServerApi}admin`,admin)
        }



        getAdminById(id: number): Observable<Admin> {
        return this.http.get<any>(`${this.urlServerApi}admin/${id}`)
        }


        editeAdmin(id:number, admin: {}): Observable<any> {
          return this.http.put(`${this.urlServerApi}admin/${id}`, admin);
        }

        deleteAdmin(id: number): Observable<Admin>{
          return this.http.delete<Admin>(`${this.urlServerApi}admin/${id}`);
        }


}
