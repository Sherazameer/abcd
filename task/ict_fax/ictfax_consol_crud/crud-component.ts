// import { Component, OnInit } from '@angular/core';
import { Component, OnInit, ElementRef, ViewChild, TemplateRef } from '@angular/core';
import { MatSort, Sort } from '@angular/material/sort';
import { MatPaginator } from '@angular/material/paginator';
import { ModalComponent } from '../../modal.component';
import { NgbModal, ModalDismissReasons, NgbModalRef } from '@ng-bootstrap/ng-bootstrap';
import { Observable } from 'rxjs/Rx';
import { AppService } from '../../app.service';
import { NbWindowRef, NbWindowService } from '@nebular/theme';
import { TranslateService } from '@ngx-translate/core';
import { CrudService } from './crud.service';
import { Crud } from './crud';

@Component({
  selector: 'ngx-crud',
  templateUrl: './crud-component.html',
  styleUrls: ['./crud-component.scss']
})
export class CrudComponent implements OnInit {

  aCrud: Crud[];
  displayedColumns = ['ID', 'firstName', 'lastName', 'Phone', 'Email', 'Operations'];

  @ViewChild(MatSort) sort: MatSort;

  @ViewChild(MatPaginator) paginator: MatPaginator;

  @ViewChild('filter') filter: ElementRef;

  constructor(private crudService: CrudService) { }

  ngOnInit(): void {
    this.loadCruds();
  }

  loadCruds(): void {
    this.aCrud = this.crudService.getAll();
    console.log('Loaded data:', this.aCrud);
  }

  deleteCrud(id: number): void {
    this.crudService.delete_Crud(id).then(() => this.loadCruds());
  }
}

