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
    await this.CacheAndMatchFaxList();
    this.timerSubscription = setInterval(() => this.CacheAndMatchFaxList(), 30 * 1000); // Refresh every 2 minutes
    // this.timerSubscription = setInterval(() => this.CacheAndMatchFaxList(), 2 * 60 * 1000); // Refresh every 2 minutes
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



  async CacheAndMatchFaxList() {
    try {
      // Attempt to retrieve cached data
      const cachedResponse = await caches.match(new Request('/faxlist'));
      let cachedData = cachedResponse ? await cachedResponse.json() : null;
  
      if (cachedData) {
        const isExpired = this.isCacheExpired(cachedData.timestamp);
        console.log(`Cache expired: ${isExpired}`); // Logging cache expiration status
  
        if (!isExpired) {
          // If cache is valid, set the data source
          this.setDataSource(cachedData.data);
          return; // Exit the function as we have valid cached data
        }
      }
  
      // If no cached data or cache is expired, fetch new data
      const data = await this.sendfax_service.get_OutFaxTransmissionList();
      this.aSendFax = data.sort((a, b) => b.transmission_id - a.transmission_id);
      this.length = data.length;
 

  
      // Create a new cache data object
      const newCacheData = {
        timestamp: Date.now(),
        data: data
      };
  
      // Create a response object for caching
      const response = new Response(JSON.stringify(newCacheData), {
        headers: { 'Content-Type': 'application/json' },
      });
  
      // Open a cache and store the new response
      const cache = await caches.open('fax-cache');
      await cache.put(new Request('/faxlist'), response);
  
      // Set the data source with the new data
      this.setDataSource(newCacheData.data);
  
    } catch (error) {
      console.error('Error handling fax list cache:', error);
      // Set the data source to an empty list in case of an error
      this.setDataSource([]);
    }
  }
  
  setDataSource(data: SendFax[]) {
    if (data && data.length > 0) {
      this.SendFaxDataSource = this.dataSourceBuilder.create(
        data.map(item => ({ data: item }))
      );
    }
    //  else {
    //   // Handle the case when data is empty or null
    //   this.SendFaxDataSource = this.dataSourceBuilder.create([]);
    // }
  }


  isCacheExpired(timestamp: number): boolean {
    // const cacheDuration = 2 * 60 * 1000; // 2 minutes in milliseconds
    const cacheDuration = 30 * 1000; // 30 seconds in milliseconds
    const currentTime = Date.now();
    const timeDifference = currentTime - timestamp;
    const expired = timeDifference > cacheDuration;
    console.log(`Cache duration: ${cacheDuration}`);
    console.log(`Cache expired: ${expired}`); // Logging cache expiration status
    return expired;
  }



  downloadDocument(document_id) {
    this.documnet_service.get_Documentdownload(document_id);
  }

  async deleteDocument(transmission_id: number) {
    try {
      // Delete the document
      await this.sendfax_service.delete_Document(transmission_id);

      // Invalidate the cache
      const cache = await caches.open('fax-cache');
      await cache.delete(new Request('/faxlist'));

      // Fetch and set the updated fax list
      await this.CacheAndMatchFaxList();
    } catch (error) {
      console.error('Error deleting document:', error);
    }
  }
  // deleteDocument(transmission_id) {
  //   this.sendfax_service.delete_Document(transmission_id)
  // }
}
