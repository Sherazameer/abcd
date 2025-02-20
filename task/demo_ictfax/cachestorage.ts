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
    await this.getFaxlist();
    await this.CacheStorage_FaxList();
    this.timerSubscription = setInterval(() => this.CacheStorage_FaxList(), 20 * 1000); // Refresh every 2 minutes
  }


  async CacheStorage_FaxList() {
    try {
        const exist_cached = await caches.match(new Request('/faxlist'));
        let cachedData = null;
        if (exist_cached) {
            cachedData = await exist_cached.json(); // Read the cached response once
            this.SendFaxDataSource = this.dataSourceBuilder.create(cachedData.data.map(item => ({ data: item })));
            console.log('Cache clone: true');
        }

        let expire_cache = cachedData; // Use the cached data we just read
        if (expire_cache && (Date.now() - expire_cache.timestamp) <= (20 * 1000)) { // 20 seconds in milliseconds
            if (expire_cache.data && expire_cache.data.length > 0) {
                this.SendFaxDataSource = this.dataSourceBuilder.create(expire_cache.data.map(item => ({ data: item })));
            }
            console.log('Cache expire_cache: true');

        } 
        else {
            console.log(`Cache expired: true`); // Logging cache expiration status
            const data = await this.getFaxlist(); // Fetch new data
            const new_cache = { timestamp: Date.now(), data }; 
            // Store the new cache
            await (await caches.open('fax-cache')).put(new Request('/faxlist'), new Response(JSON.stringify(new_cache), { headers: { 'Content-Type': 'application/json' } }));
            if (new_cache.data && new_cache.data.length > 0) {
                this.SendFaxDataSource = this.dataSourceBuilder.create(new_cache.data.map(item => ({ data: item })));
            }
        }

    } catch (error) {
        console.error('Error handling fax list cache:', error);
    }
}

async getFaxlist() {
    return this.sendfax_service.get_OutFaxTransmissionList().then(data => {
        this.aSendFax = data.sort((a, b) => b.transmission_id - a.transmission_id);
        this.length = data.length;

        data.forEach(element => {
            if (element.contact_phone == null) {
                element.contact_phone = 'N/A';
            }
        });
        this.SendFaxDataSource = this.dataSourceBuilder.create(data.map(item => ({ data: item })),);

        return data; // Return the data to be used in CacheStorage_FaxList
    });
}

//   async CacheStorage_FaxList() {
//     try {
//       const exist_cached = await caches.match(new Request('/faxlist')); // Attempt to retrieve cached data
//       const cachedResponse = exist_cached.clone();

//       const cachedData = await cachedResponse.json(); // Assuming the cached response is in JSON format
//       this.SendFaxDataSource = this.dataSourceBuilder.create(cachedData.data.map(item => ({ data: item })));
//       console.log(`Cache clone: true`); // Logging cache expiration status

         
//         let expire_cache = exist_cached ? await exist_cached.json() : null;
//         if (expire_cache && (Date.now() - expire_cache.timestamp) <= (20 * 1000)) { // 30 seconds in milliseconds
//             if (expire_cache.data && expire_cache.data.length > 0) {
//                 this.SendFaxDataSource = this.dataSourceBuilder.create(expire_cache.data.map(item => ({ data: item })));
//             }
//         }else{
//         console.log(`Cache expired: true`); // Logging cache expiration status
//         const data = await this.getFaxlist(); // Fetch new data
//         const new_cache = { timestamp: Date.now(), data }; // Create a new cache data object

//         await (await caches.open('fax-cache')).put(new Request('/faxlist'),  new Response(JSON.stringify(new_cache), { headers: { 'Content-Type': 'application/json' } }));

//         if (new_cache.data && new_cache.data.length > 0) {
//             this.SendFaxDataSource = this.dataSourceBuilder.create(new_cache.data.map(item => ({ data: item })));
//         }
//       }

//     } catch (error) {
//         console.error('Error handling fax list cache:', error);
//     }
// }


// async getFaxlist() {
//   this.sendfax_service.get_OutFaxTransmissionList().then(data => {
//     this.aSendFax = data.sort((a, b) => b.transmission_id - a.transmission_id);
//     this.length = data.length;

//     data.forEach(element => {
//       if (element.contact_phone == null) {
//         element.contact_phone = 'N/A';
//       }
//     })
//     // this.SendFaxDataSource = this.dataSourceBuilder.create(data.map(item => ({ data: item })),);
//   });
// }


  // async getFaxlist() {
  //   try {
  //     const data = await this.sendfax_service.get_OutFaxTransmissionList();
  //     this.aSendFax = data.sort((a, b) => b.transmission_id - a.transmission_id);
  //     this.length = data.length;
  //     data.forEach(element => {
  //       if (element.contact_phone == null) {
  //         element.contact_phone = 'N/A';
  //       }
  //     });
//   const processedData = data.map(element => ({
//     ...element,
//     contact_phone: element.contact_phone || 'N/A'
// }));
// this.SendFaxDataSource = this.dataSourceBuilder.create(processedData.map(item => ({ data: item })));

  //     // this.SendFaxDataSource = this.dataSourceBuilder.create(data.map(item => ({ data: item })));
  // return processedData; // Return the data to be used in CacheStorage_FaxList

  //     return data; // Return the fetched data
  //   } catch (error) {
  //     console.error('Error fetching and processing fax list:', error);
  //     return [];
  //   }
  // }
  




  downloadDocument(document_id) {
    this.documnet_service.get_Documentdownload(document_id);
  }

  async deleteDocument(transmission_id: number) {
    try {
      await this.sendfax_service.delete_Document(transmission_id);
      const cache = await caches.open('fax-cache');
      await cache.delete(new Request('/faxlist'));
      await this.CacheStorage_FaxList();
    } catch (error) {
      console.error('Error deleting document:', error);
    }
  }

}
