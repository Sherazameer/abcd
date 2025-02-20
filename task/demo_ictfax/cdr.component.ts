import { Component, OnInit, ViewChild } from '@angular/core';
import {FormGroup, FormControl } from '@angular/forms';
import { MatSort , Sort } from '@angular/material/sort';
import { MatPaginator } from '@angular/material/paginator';
import { AUserService } from '../user/user.service';
import { NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';

import { CDR } from './cdr';
import { CDRService } from './cdr.service';
import { CDRDataSource } from './cdr-datasource.component';
import { CDRDatabase } from './cdr-database.component';

@Component({
  selector: 'ngx-cdr',
  styleUrls: ['./cdr.component.scss'],
  templateUrl: './cdr.component.html',
})

export class CDRComponent implements OnInit {

  aCdr: CDRDataSource | null;
  tenants: any;
  users: any;
  tenant_id: number = 0;
  user_id: number = 0;
  direction: any;
  length: number;
  is_admin: any = 0;
  is_tenant: any = 0;
  filter = '';
  range: FormGroup;

  @ViewChild(MatSort) sort: MatSort;
  @ViewChild(MatPaginator) paginator: MatPaginator;
  displayedColumns= [ 'time_connect', 'company', 'username', 'contact_phone', 'direction', 'account_phone', 'status', 'pages'];

  constructor(private cdr_service: CDRService,   private modalService: NgbModal,  private user_service: AUserService) { }


  ngOnInit() {
    this.range = new FormGroup({
        start: new FormControl(null),
        end: new FormControl(null)
    });

    this.is_admin = Number(localStorage.getItem('is_admin'));
    this.is_tenant = Number(localStorage.getItem('is_tenant'));
    
    if (this.is_admin == 1) this.getAllTenants();
    if (this.is_admin == 0) this.tenant_id = Number(localStorage.getItem('tenant_id'));

    this.getAllUsers(this.tenant_id);
    this.getList();

    this.range.valueChanges.subscribe(() => {
        this.setFilter(); 
        this.getList();
    });
}




  getList() {
    
    this.setFilter();
    this.cdr_service.get_CDRlist(this.filter).then(response => {
      this.length = response.length;
      this.aCdr = new  CDRDataSource(new CDRDatabase( response ), this.sort, this.paginator);
      const sortState: Sort = {active: 'time_connect', direction: 'desc'};
      this.sort.active = sortState.active;
      this.sort.direction = sortState.direction;
      this.sort.sortChange.emit(sortState);
    })
  }

  getAllTenants() {
    this.user_service.get_TenantList().then(response => {
      this.tenants = response;
    })
  }

  getAllUsers(tenant = 0) {
    this.user_service.get_UserList(tenant).then(response => {
      this.users = response;
    })
  }


  openSearchModal(searchTemplate) {
    const modalRef = this.modalService.open(searchTemplate);
    modalRef.result.then(
      (result) => {
      },
      (reason) => {
      }
    );
  }

  



  filterList(modal) {
    this.setFilter();
    this.getList();
    modal.close();
}


  
  setFilter() {
    this.filter = '';
    if (this.range.value.start) this.filter += `&from=${(new Date(this.range.value.start).getTime() / 1000)}`;
    if (this.range.value.end) this.filter += `&to=${(new Date(this.range.value.end).getTime() / 1000)}`;
    if (this.tenant_id > 0) this.filter += `&tenant_id=${this.tenant_id}`;
    if (this.user_id > 0) this.filter += `&user_id=${this.user_id}`;
    if (this.direction) this.filter += `&direction=${this.direction}`;
  }

  onTenantSelect(value) {
    if (value != 0) this.tenant_id = value;
    else this.tenant_id = 0;
    this.getAllUsers(this.tenant_id);
    this.user_id = 0;
  }

  onUserSelect(value) {
    if (value != 0) this.user_id = value;
    else this.user_id = 0;
  }

  onDirectionSelect(value) {
    if (value != '') this.direction = value;
    else this.direction = undefined;
  }





  exportCDR(monthly = false): void {
    if (monthly) this.cdr_service.exportCDR(monthly);
    else {
      this.setFilter();
      this.cdr_service.exportCDR(monthly, this.filter);
    }
  }
  
  
}
