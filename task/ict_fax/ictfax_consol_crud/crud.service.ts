import { Injectable } from '@angular/core';
import { Crud } from './crud';
import { AppService } from '../../../app/app.service';
import 'rxjs/add/operator/toPromise';

@Injectable({
  providedIn: 'root'
})
export class CrudService {

  private storageKey = 'crudData';

  constructor() {
    if (!localStorage.getItem(this.storageKey)) {
      localStorage.setItem(this.storageKey, JSON.stringify([])); // Start with an empty array
    }
  }

  get_CrudData(id: number): Promise<Crud> {
    const cruds = this.getAll();
    return Promise.resolve(cruds.find(crud => crud.crud_id === id));
  }

  getAll(): Crud[] {
    return JSON.parse(localStorage.getItem(this.storageKey)) || [];
  }

  add_Crud(crud: Crud): Promise<void> {
    const cruds = this.getAll();
    crud.crud_id = this.getNextId();
    cruds.push(crud);
    this.save(cruds);
    console.log('Created:', crud);
    return Promise.resolve();
  }

  update_Crud(crud: Crud): Promise<void> {
    const cruds = this.getAll();
    const index = cruds.findIndex(c => c.crud_id === crud.crud_id);
    if (index > -1) {
      cruds[index] = crud;
      this.save(cruds);
      console.log('Updated:', crud);
    }
    return Promise.resolve();
  }

  delete_Crud(id: number): Promise<void> {
    let cruds = this.getAll();
    cruds = cruds.filter(crud => crud.crud_id !== id);
    this.save(cruds);
    console.log('Deleted ID:', id);
    return Promise.resolve();
  }

  private save(cruds: Crud[]): void {
    localStorage.setItem(this.storageKey, JSON.stringify(cruds));
  }

  private getNextId(): number {
    const cruds = this.getAll();
    return cruds.length > 0 ? Math.max(...cruds.map(c => c.crud_id)) + 1 : 1;
  }
}












// import { Injectable } from '@angular/core';
// import { Headers } from '@angular/http';
// import { Http, Response, HttpModule, RequestOptions } from '@angular/http';
// import { Crud } from './crud';
// import { AppService } from '../../../app/app.service';

// import 'rxjs/add/operator/toPromise';

// @Injectable()

// export class CrudService {

//   aCrud: Crud[]= [];
//   crud_id: any= null;
//   crud: Crud= new Crud;

//   constructor(private http: Http, private app_service: AppService) {}

//   get_CrudList() {
//     const headers = new Headers();
//     this.app_service.createAuthorizationHeader(headers);
//     const options = new RequestOptions({ headers: headers});
//     return this.http.get(this.app_service.apiUrlCruds, options).toPromise()
//     .then(response => response.json() ).catch(response => this.app_service.handleError(response));
//   }

//   get_CrudData(crud_id): Promise<Crud> {
//     const headers = new Headers();
//     this.app_service.createAuthorizationHeader(headers);
//     const options = new RequestOptions({ headers: headers});
//     const url5 = `${this.app_service.apiUrlCruds}/${crud_id}`;
//     return this.http.get(url5, options).toPromise()
//     .then(response => response.json() as Crud).catch(response => this.app_service.handleError(response));
//   }

//   add_Crud(crud: Crud): Promise<Crud> {
//     const headers = new Headers();
//     this.app_service.createAuthorizationHeader(headers);
//     const options = new RequestOptions({headers: headers});
//     const body = JSON.stringify(crud);
//     const addUrl = `${this.app_service.apiUrlCruds}`;
//     return this.http.post(addUrl, body, options).toPromise().then(response => response.json() as Crud)
//     .catch(response => this.app_service.handleError(response));
//   }

//   update_Crud(crud: Crud): Promise<Crud> {
//     const headers = new Headers();
//     this.app_service.createAuthorizationHeader(headers);
//     const options = new RequestOptions({headers: headers});
//     const body = JSON.stringify(crud);
//     const updateUrl = `${this.app_service.apiUrlCruds}/${crud.crud_id}`;
//     return this.http.put(updateUrl, body, options).toPromise().then(response => response.json() as Crud)
//     .catch(response => this.app_service.handleError(response));
//   }

//   delete_Crud(crud_id): Promise<any> {
//     const headers = new Headers();
//     this.app_service.createAuthorizationHeader(headers);
//     const options = new RequestOptions({headers: headers});
//     const deleteUrl = `${this.app_service.apiUrlCruds}/${crud_id}`;
//     return this.http.delete(deleteUrl, options).toPromise().then(response => response.json() as Crud)
//     .catch(response => this.app_service.handleError(response));
//   }

//   private handleError(error: any): Promise<any> {
//     console.error('An error occurred', error); // for demo purposes only
//     return Promise.reject(error.message || error);
//   }
// }
