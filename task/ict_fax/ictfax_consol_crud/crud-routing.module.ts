import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CrudzComponent } from './crud.component';
import { AddCrudComponent } from './crud-form-component';
import { CrudComponent } from './crud-component';

import { CrudComponent as FormsCrudComponent } from './crud-component';  // Rename if using the same logic

const routes: Routes = [{
  path: '',
  component: CrudzComponent,

  children: [{
    path: 'crud',
    component: FormsCrudComponent,
  },

  {
    path: 'cruds/new',
    component: AddCrudComponent,
  }, {
    path: 'cruds/:id',
    component: AddCrudComponent,
  }, {
    path: 'cruds/:id/delete',
    component: FormsCrudComponent,
  }],
}];
@NgModule({
  imports: [
    RouterModule.forChild(routes),
  ],
  exports: [
    RouterModule,
  ],
})
export class CRUDRoutingModule {

}

export const routedComponents = [
 CrudzComponent,
 FormsCrudComponent,
 AddCrudComponent,
 CrudComponent
];
