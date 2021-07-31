import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { BehaviorSubject } from 'rxjs';
import { AuthService } from 'src/app/services/auth.service';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {


  credentials = {"username": 'admin@gmail.com', "password":'SEN-INTERIM'};
  private role: any;

    constructor(
        private formBuilder: FormBuilder,
        private route: ActivatedRoute,
        private router: Router,
        private authenService: AuthService
    ) {}

    ngOnInit() {}


    login() {
      
      this.authenService.login(this.credentials).subscribe(
        (result)  => {    
          localStorage.setItem('token', result.token);
          this.role = this.authenService.getRole(result.token);
        if (this.role[0] == 'ROLE_ADMINSYSTEME') {
          this.router.navigate(['admin/driver']);
        }
       
        }
      );
    }

    logout() {
      this.authenService.logout();
      this.router.navigate(['/']);
    }


}