import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CRUDRoutingModule,routedComponents } from './crud-routing.module';
import { NbButtonModule, NbCardModule, NbIconModule } from '@nebular/theme';
import { MatTableModule } from '@angular/material/table';
import { CdkTable, CdkTableModule } from '@angular/cdk/table';
import { MatSort, MatSortModule } from '@angular/material/sort';
import { FileUploadModule } from 'ng2-file-upload';
import { MatButtonModule } from '@angular/material/button';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatIconModule } from '@angular/material/icon';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { Ng2CompleterModule } from 'ng2-completer';
import { CrudService } from './crud.service';

@NgModule({
  imports: [
    CommonModule,
    CRUDRoutingModule,
    NbCardModule,
    MatTableModule,
    CdkTableModule,
    MatSortModule,
    FileUploadModule,
    MatButtonModule,
    MatPaginatorModule,
    MatIconModule,
    NbCardModule,
    FormsModule,
    ReactiveFormsModule,
    NbIconModule,
    TranslateModule,
    Ng2CompleterModule,
    NbButtonModule


  ],
  declarations: [
    ...routedComponents,
  ],
  providers:[CrudService]
})
export class CrudModule { }
