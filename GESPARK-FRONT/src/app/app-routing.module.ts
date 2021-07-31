import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AdminComponent } from './components/admin/admin.component';
import { AgentComponent } from './components/agent/agent.component';
import { AddDriverComponent } from './components/driver/add-driver/add-driver.component';
import { DriverComponent } from './components/driver/driver.component';
import { SingleDriverComponent } from './components/driver/single-driver/single-driver.component';
import { InsuranceComponent } from './components/insurance/insurance.component';
import { LoginComponent } from './components/login/login.component';
import { NotificationComponent } from './components/notification/notification.component';
import { TechniqueComponent } from './components/technique/technique.component';
import { AddVehicleComponent } from './components/vehicle/add-vehicle/add-vehicle.component';
import { DetailDriverComponent } from './components/vehicle/detail-driver/detail-driver.component';
import { DetailFileComponent } from './components/vehicle/detail-file/detail-file.component';
import { DetailInsuranceComponent } from './components/vehicle/detail-insurance/detail-insurance.component';
import { PanneComponent } from './components/vehicle/panne/panne.component';
import { SingleVehicleComponent } from './components/vehicle/single-vehicle/single-vehicle.component';
import { VehicleComponent } from './components/vehicle/vehicle.component';
import { AuthGuard } from './guard/auth.guard';

const routes: Routes = [
  {
    path: 'login',
    component: LoginComponent,
  },
  {
    path: 'admin',
    component: AdminComponent,
    children: [
        {
          path: 'driver',
          component: DriverComponent,
        },
        {
          path: 'driver/:codeUser',
          component: SingleDriverComponent
        },
        {
          path: 'driver/add/new',
          component: AddDriverComponent,
        },
        {
          path: 'vehicle',
          component: VehicleComponent
        },
        {
          path: 'vehicle/:codeVehicle',
          component: SingleVehicleComponent,
          children: [
              {
                path: 'assurance',
                component: DetailInsuranceComponent
              },
              {
                path: 'carte-a-grise',
                component: DetailFileComponent
              },
              {
                path: 'conducteur',
                component: DetailDriverComponent
              }
          ]
        },
        {
          path: 'vehicle/add/new',
          component: AddVehicleComponent,
        },
        {
          path: 'notification',
          component: NotificationComponent
        },
        {
          path: 'insurance',
          component: InsuranceComponent
        },
        {
          path: 'technique',
          component: TechniqueComponent
        },
        {
          path: 'panne',
          component: PanneComponent
        }

    ]
  },
  {
    path: 'agent',
    component: AgentComponent,
  },


  {
    path: '',
    redirectTo: '/login',
    pathMatch: 'full'
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
