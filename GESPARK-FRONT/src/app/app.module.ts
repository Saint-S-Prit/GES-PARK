import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { LoginComponent } from './components/login/login.component';
import { AdminComponent } from './components/admin/admin.component';
import { AgentComponent } from './components/agent/agent.component';
import { DriverComponent } from './components/driver/driver.component';
import { VehicleComponent } from './components/vehicle/vehicle.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AuthService } from './services/auth.service';
import { TokenInterceptorService } from './_helpers/token-interceptor.service';
import { NotificationComponent } from './components/notification/notification.component';
import { InsuranceComponent } from './components/insurance/insurance.component';
import { TechniqueComponent } from './components/technique/technique.component';
import { SingleDriverComponent } from './components/driver/single-driver/single-driver.component';
import { AddDriverComponent } from './components/driver/add-driver/add-driver.component';
import { AddVehicleComponent } from './components/vehicle/add-vehicle/add-vehicle.component';
import { SingleVehicleComponent } from './components/vehicle/single-vehicle/single-vehicle.component';
import { DetailDriverComponent } from './components/vehicle/detail-driver/detail-driver.component';
import { DetailFileComponent } from './components/vehicle/detail-file/detail-file.component';
import { DetailInsuranceComponent } from './components/vehicle/detail-insurance/detail-insurance.component';
import { DetailTechniqueComponent } from './components/vehicle/detail-technique/detail-technique.component';
import { AdminService } from './services/admin.service';
import { DriverService } from './services/driver.service';
import { InsuranceService } from './services/insurance.service';
import { TechniqueService } from './components/technique/technique.service';
import { VehicleService } from './services/vehicle.service';
import { PanneComponent } from './components/vehicle/panne/panne.component';
import { UploardFileService } from './services/uploard-file.service';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    AdminComponent,
    AgentComponent,
    DriverComponent,
    VehicleComponent,
    NotificationComponent,
    InsuranceComponent,
    TechniqueComponent,
    SingleDriverComponent,
    AddDriverComponent,
    AddVehicleComponent,
    SingleVehicleComponent,
    DetailDriverComponent,
    DetailFileComponent,
    DetailInsuranceComponent,
    DetailTechniqueComponent,
    PanneComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule ,
    FormsModule,
    ReactiveFormsModule
  ],
  providers: [
    AdminService,
    DriverService,
    InsuranceService,
    TechniqueService,
    VehicleService,
    AuthService,
    UploardFileService,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: TokenInterceptorService,
      multi: true
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
