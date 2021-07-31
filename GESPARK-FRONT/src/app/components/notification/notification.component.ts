import { Component, OnInit } from '@angular/core';
import { NotificationService } from './notification.service';

@Component({
  selector: 'app-notification',
  templateUrl: './notification.component.html',
  styleUrls: ['./notification.component.css']
})
export class NotificationComponent implements OnInit {
  notification : any;
  constructor(private notifService: NotificationService) {  }

  ngOnInit(): void {
    console.log(this.getNotification());
    
  }

  getNotification()
  {
    this.notifService.getAll().subscribe(
      (notification) => {
        this.notification = notification
      }
    )
  }
}
