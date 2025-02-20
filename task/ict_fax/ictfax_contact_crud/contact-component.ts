import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import { ContactedService } from './contacted.service';
import { MatSort } from '@angular/material/sort';
import { MatPaginator } from '@angular/material/paginator';
import { ContactDatabase } from './contact-database.component';
import { ContactDataSource } from './contact-datasource.component';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Contact } from './contact';
import { AppService } from '../../app.service';
import { Router } from '@angular/router';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'ngx-contact-component',
  templateUrl: './contact-component.html',
  styleUrls: ['./contact-component.scss'],
})
export class FormsContactComponent implements OnInit {
  aContact: ContactDataSource | null;
  length: number;
  closeResult: any;
  contactArray: Contact[] = [];
  trans_id: any;

  public file_sending = false;
  public file_sent = false;

  isError = false;
  errorText: any = [];

  displayedColumns = ['ID', 'firstName', 'lastName', 'Phone', 'Email', 'Operations'];

  @ViewChild(MatSort) sort: MatSort;
  @ViewChild(MatPaginator) paginator: MatPaginator;
  @ViewChild('filter') filter: ElementRef;

  constructor(private contact_service: ContactedService,
              private modalService: NgbModal,
              private app_service: AppService,
              private router: Router,
              private translateService: TranslateService) { }

  ngOnInit(): void {
    this.getContactlist();
  }

  getContactlist() {
    this.contact_service.get_ContactList().then(data => {
      this.length = data.length;
      this.aContact = new ContactDataSource(new ContactDatabase(data), this.sort, this.paginator);
    });
  }

  deleteContact(contact_id: string): void {
    this.contact_service.delete_Contact(contact_id)
      .then(() => {
        this.getContactlist();
      })
      .catch(error => this.handleError(error));
  }



  private handleError(error: any): void {
    console.error('An error occurred:', error); // for demo purposes only
    this.isError = true;
    this.errorText.push(error.message || error);
  }
}
