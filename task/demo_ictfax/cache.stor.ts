import { NgModule, Component, EventEmitter, ElementRef, Input, Output, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { SendFaxService } from './sendfax.service';
import { SendFax } from './sendfax';
import { Http, RequestOptions, Headers } from '@angular/http';
import { ContactService } from '../contact/contact.service';
import 'rxjs/add/operator/startWith';
import 'rxjs/add/observable/merge';
import 'rxjs/add/operator/toPromise';
import { Observable } from 'rxjs';
import 'rxjs/add/observable/fromEvent';
import { AppService } from '../../app.service';
import { TransmissionService } from '../transmission/transmission.service';
import { DocumentService } from '../message/document/document.service';
import { NbTreeGridDataSource, NbTreeGridDataSourceBuilder } from '@nebular/theme';

@Component({
  selector: 'ngx-sendfax-component',
  templateUrl: './sendfax-component.html',
  styleUrls: ['./sendfax-component.scss'],
})

export class FormsSendFaxComponent implements OnInit {
  constructor(private sendfax_service: SendFaxService,
    private dataSourceBuilder: NbTreeGridDataSourceBuilder<SendFax>,
    private contact_service: ContactService, private http: Http,
    private app_service: AppService, private documnet_service: DocumentService) { }

  aSendFax: SendFax[];
  SendFaxDataSource: NbTreeGridDataSource<SendFax>;
  public length: number;
  private timerSubscription: any;
  displayedColumns = ['transmission_id', 'phone', 'Timestamp', 'username', 'status', 'Operations'];

  @ViewChild('filter', { static: false }) filter: ElementRef;

  async ngOnInit() {
    await this.Match_CacheFaxList();
    this.timerSubscription = setInterval(() => this.Match_CacheFaxList(), 30 * 1000); // Refresh every 2 minutes
    // this.timerSubscription = setInterval(() => this.Match_CacheFaxList(), 2 * 60 * 1000); // Refresh every 2 minutes
  }

  async Match_CacheFaxList() {
    const cachedData = await this.getFaxlistFromCache();
    if (cachedData) {
      const isExpired = this.isCacheExpired(cachedData.timestamp);
      console.log(`Cache expired: ${isExpired}`); // Logging cache expiration status
      if (!isExpired) {
        this.setDataSource(cachedData.data);
      } else {
        await this.Cache_Storage();
        const newCachedData = await this.getFaxlistFromCache();
        if (newCachedData) {
          this.setDataSource(newCachedData.data);
        }
      }
    } else {
      await this.Cache_Storage();
      const newCachedData = await this.getFaxlistFromCache();
      if (newCachedData) {
        this.setDataSource(newCachedData.data);
      }
    }
  }

  // async getFaxlist() {
  //   this.sendfax_service.get_OutFaxTransmissionList().then(data => {
  //     this.aSendFax = data.sort((a, b) => b.transmission_id - a.transmission_id);
  //     this.length = data.length;

  //     data.forEach(element => {
  //       if (element.contact_phone == null) {
  //         element.contact_phone = 'N/A';
  //       }
  //     });
  //     this.SendFaxDataSource = this.dataSourceBuilder.create(data.map(item => ({ data: item })));
  //   });
  // }

  async Cache_Storage() {
    try {
      const data = await this.sendfax_service.get_OutFaxTransmissionList();
      this.aSendFax = data.sort((a, b) => b.transmission_id - a.transmission_id);
      this.length = data.length;
      const cacheData = {
        timestamp: Date.now(), data: data
      };
      // Create a response object
      const response = new Response(JSON.stringify(cacheData), {
        headers: { 'Content-Type': 'application/json' },
      });

      // Open a cache and store the response
      const { cache, request } = await this.Cache_open(); // Open cache    
      await cache.put(request, response);

    } catch (error) {
      console.error('Error fetching fax list:', error);
    }
  }

  async getFaxlistFromCache() {
    const { cache, request } = await this.Cache_open(); // Open cache    
    const response = await cache.match(request);
    console.log('Exist data read from cache');

    if (response) {
      const cacheData = await response.json();
      return cacheData;
    }
    return null;
  }

  async Cache_open() {
    const cache = await caches.open('fax-cache');
    const request = new Request('/faxlist');
    return { cache, request }; // Return both cache and request
}

  isCacheExpired(timestamp: number): boolean {
    // const cacheDuration = 2 * 60 * 1000; // 2 minutes in milliseconds
    const cacheDuration = 30 * 1000; // 30 seconds in milliseconds
    const currentTime = Date.now();
    const timeDifference = currentTime - timestamp;
    const expired = timeDifference > cacheDuration;
    console.log(`Current time: ${currentTime}`);
    console.log(`Cached time: ${timestamp}`);
    console.log(`Time difference: ${timeDifference}`);
    console.log(`Cache duration: ${cacheDuration}`);
    console.log(`Cache expired: ${expired}`); // Logging cache expiration status
    return expired;
  }

  setDataSource(data: SendFax[]) {
    if (data) {
      this.SendFaxDataSource = this.dataSourceBuilder.create(
        this.aSendFax.map(item => ({ data: item }))
      );
    }
  }

  downloadDocument(document_id) {
    this.documnet_service.get_Documentdownload(document_id);
  }

  deleteDocument(transmission_id) {
    this.sendfax_service.delete_Document(transmission_id)
  }
}
