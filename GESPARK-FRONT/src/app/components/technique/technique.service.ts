import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class TechniqueService {


    //get url api to d'environmrnt
    private urlServerApi = environment.serverApi;

    constructor(
        private http: HttpClient,
    ) {}


        getTechnique(): Observable<any[]> {
          return this.http.get<any[]>(`${this.urlServerApi}technique`)
        }
}
