import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ContactComponent } from './contact.component';
import { FormsContactComponent } from './contact-component';
import { AddContactComponent } from './contact-form-component';

const routes: Routes = [{
  path: '',
  component: ContactComponent,

  children: [{
    path: 'sheraz',
    component: FormsContactComponent,
  },

  {
    path: 'sheraz/new',
    component: AddContactComponent,
  }, {
    path: 'sheraz/:id',
    component: AddContactComponent,
  }, {
    path: 'sheraz/:id/delete',
    component: FormsContactComponent,
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
export class ContactRoutingModule {

}

export const routedComponents = [
  ContactComponent,
  FormsContactComponent,
  AddContactComponent,
  ContactComponent,

];
