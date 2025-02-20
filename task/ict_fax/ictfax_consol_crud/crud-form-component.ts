import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { Crud } from './crud';
import { CrudService } from './crud.service';
import 'rxjs/add/operator/toPromise';

@Component({
  selector: 'ngx-add-crud-component',
  templateUrl: './crud-form-component.html',
  styleUrls: ['./crud-form-component.scss'],
})
export class AddCrudComponent implements OnInit {
  crud: Crud = new Crud();
  crud_id: any = null;

  isError: boolean = false;
  errorText: string[] = [];

  constructor(
    private route: ActivatedRoute,
    private crud_service: CrudService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.route.params.subscribe((params) => {
      this.crud_id = +params['id'];
      const test_url = this.router.url.split('/');
      const lastsegment = test_url[test_url.length - 1];
      if (lastsegment === 'new') {
        return null;
      } else {
        return this.crud_service.get_CrudData(this.crud_id).then(data => {
          this.crud = data;
        });
      }
    });
  }

  addCrud(): void {
    this.checkFields();
    if (this.errorText.length === 0) {
      this.crud_service.add_Crud(this.crud).then((response) => {
        this.router.navigate(['../../crud'], { relativeTo: this.route });
        //   this.router.navigate(['/pages/crud/cruds']); // Correcting the path
      });
          } else {
      this.errorHandler(true, this.errorText);
    }
  }
  // addCrud(): void {
  //   this.checkFields();
  //   if (this.errorText.length === 0) {
  //     this.crud_service.add_Crud(this.crud).then(() => {
  //       console.log('Navigation successful to: /pages/crud/crud');
  //       this.router.navigate(['/pages/crud/crud']);
  //     }).catch((error) => {
  //       console.error('Error during navigation:', error);
  //       this.handleError(error);
  //     });
  //   } else {
  //     this.errorHandler(true, this.errorText);
  //   }
  // }

  updateCrud(): void {
    this.checkFields();
    if (this.errorText.length === 0) {
      this.crud_service.update_Crud(this.crud).then(() => {
        this.router.navigate(['../../crud'], { relativeTo: this.route });
        // this.router.navigate(['/pages/crud/cruds']); // Correcting the path
      });
    } else {
      this.errorHandler(true, this.errorText);
    }
  }
  //
  // deleteCrud(crudId: number) {
  //   this.crud_service.delete_Crud(crudId).then(() => {
  //   }).catch(error => {
  //     console.error('Error deleting crud:', error);
  //   });
  // }

  private checkFields(status = null): any {
    this.errorHandler(false, []);
    if (!this.crud.phone) this.errorText.push('Phone is required.');
  }

  private errorHandler(status: boolean, message: string[]): void {
    this.isError = status;
    this.errorText = message;
    if (status) {
      setTimeout(() => {
        this.isError = false;
        this.errorText = [];
      }, 10000);
    }
  }

  private handleError(error: any): Promise<any> {
    // console.error('An error occurred', error);
    console.log('Navigating to:', '/pages/crud/cruds', error);

    return Promise.reject(error.message || error);
  }
}
