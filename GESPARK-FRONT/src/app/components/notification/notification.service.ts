import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Notification } from 'src/app/modele/notification';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class NotificationService {

    //get url api to d'environmrnt
    private urlServerApi = environment.serverApi;

    constructor(
        private http: HttpClient
    ) {}

        getAll(): Observable<Notification[]> {
          return this.http.get<Notification[]>(`${this.urlServerApi}notification`)
        }
        
}
