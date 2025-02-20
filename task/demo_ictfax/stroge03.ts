  async CacheStorage_FaxList() {
    try {
      const exist_cached = await caches.match(new Request('/faxlist')); // Attempt to retrieve cached data
      const cachedResponse = exist_cached.clone();

      const cachedData = await cachedResponse.json(); // Assuming the cached response is in JSON format
      this.SendFaxDataSource = this.dataSourceBuilder.create(cachedData.data.map(item => ({ data: item })));
      console.log(`Cache clone: true`); // Logging cache expiration status

         
        let expire_cache = exist_cached ? await exist_cached.json() : null;
        // Check if cached data is valid (not expired)
        if (expire_cache && (Date.now() - expire_cache.timestamp) <= (20 * 1000)) { // 30 seconds in milliseconds
            // console.log(`Cache expired: false`); // Logging cache expiration status
            if (expire_cache.data && expire_cache.data.length > 0) {
                this.SendFaxDataSource = this.dataSourceBuilder.create(expire_cache.data.map(item => ({ data: item })));
            }
        }else{
        console.log(`Cache expired: true`); // Logging cache expiration status
        const data = await this.getFaxlist(); // Fetch new data
        const new_cache = { timestamp: Date.now(), data }; // Create a new cache data object

        await (await caches.open('fax-cache')).put(new Request('/faxlist'),  new Response(JSON.stringify(new_cache), { headers: { 'Content-Type': 'application/json' } }));

        if (new_cache.data && new_cache.data.length > 0) {
            this.SendFaxDataSource = this.dataSourceBuilder.create(new_cache.data.map(item => ({ data: item })));
        }
      }

    } catch (error) {
        console.error('Error handling fax list cache:', error);
    }
}

02
 async CacheStorage_FaxList() {
    try {
        const exist_cached = await caches.match(new Request('/faxlist')); // Attempt to retrieve cached data
        if (exist_cached) {
            const cachedResponse = exist_cached.clone();
            const cachedData = await cachedResponse.json(); // Assuming the cached response is in JSON format

            console.log(`Cache exists data read == if  run==ok--01: true`); // Logging cache existence status

            // Check if cached data is valid (not expired)
            if (cachedData && (Date.now() - cachedData.timestamp) <= (20 * 1000)) { // 20 seconds in milliseconds
              console.log(`Cache expired: if  run== true`); // Logging cache expiration status

              if (cachedData.data && cachedData.data.length > 0) {
                    this.SendFaxDataSource = this.dataSourceBuilder.create(cachedData.data.map(item => ({ data: item })));
                } else {
                    console.log('Cached data is empty.');
                }
            } else {
                console.log(`Cache expired: else part run== true`); // Logging cache expiration status
                const data = await this.getFaxlist(); // Fetch new data
                const new_cache = { timestamp: Date.now(), data }; // Create a new cache data object

                await (await caches.open('fax-cache')).put(new Request('/faxlist'), new Response(JSON.stringify(new_cache), { headers: { 'Content-Type': 'application/json' } }));

                if (new_cache.data && new_cache.data.length > 0) {
                    this.SendFaxDataSource = this.dataSourceBuilder.create(new_cache.data.map(item => ({ data: item })));
                } else {
                    console.log('Fetched new data is empty.');
                }
            }
        } else {
            console.log('data not found  Fetching new data == else part run==ok--02.');
            const data = await this.getFaxlist(); // Fetch new data
            const new_cache = { timestamp: Date.now(), data }; // Create a new cache data object

            await (await caches.open('fax-cache')).put(new Request('/faxlist'), new Response(JSON.stringify(new_cache), { headers: { 'Content-Type': 'application/json' } }));

            if (new_cache.data && new_cache.data.length > 0) {
                this.SendFaxDataSource = this.dataSourceBuilder.create(new_cache.data.map(item => ({ data: item })));
            } else {
                console.log('Fetched new data is empty.');
            }
        }
    } catch (error) {
        console.error('Error handling fax list cache:', error);
    }
}


03
  // async CacheStorage_FaxList() {
  //   try {
  //     const cachedResponse = await caches.match(new Request('/faxlist')); // Attempt to retrieve cached data
  //     let cachedData = cachedResponse ? await cachedResponse.json() : null;
    
  //     if (cachedData) {
  //       const isExpired = this.isCacheExpired(cachedData.timestamp);
  //       console.log(`Cache expired: ${isExpired}`); // Logging cache expiration status
  //       if (!isExpired) {
  //         if (cachedData.data && cachedData.data.length > 0) {
  //           this.SendFaxDataSource = this.dataSourceBuilder.create(cachedData.data.map(item => ({ data: item })));
  //       }
  //         // this.setDataSource(cachedData.data); // If cache is valid, set the data source
  //         return;
  //       }
  //     }
  //     const data = await this.getFaxlist(); // If no cached data or cache is expired, fetch new data
  //     const newCacheData = { timestamp: Date.now(), data: data }; // Create a new cache data object
  //     const response = new Response(JSON.stringify(newCacheData), { headers: { 'Content-Type': 'application/json' },});
  //     const cache = await caches.open('fax-cache');
  //     await cache.put(new Request('/faxlist'), response);
  //     if (newCacheData.data && newCacheData.data.length > 0) {
  //       this.SendFaxDataSource = this.dataSourceBuilder.create(newCacheData.data.map(item => ({ data: item })));
  //   }
  //     // this.setDataSource(newCacheData.data); // Set the data source with the new data
  
  //   } catch (error) {
  //     console.error('Error handling fax list cache:', error);
  //   }
  // }
  async CacheStorage_FaxList() {
    try {
        const exist_cached = await caches.match(new Request('/faxlist')); // Attempt to retrieve cached data
        let cachedData;

        if (exist_cached) {
            const cachedResponse = exist_cached.clone();
            cachedData = await cachedResponse.json(); // Assuming the cached response is in JSON format
            console.log(`Cache exists: true`); // Logging cache existence status
        }
        // Check if cached data is valid (not expired) or fetch new data
        if (!cachedData || (Date.now() - cachedData.expire_cache) > (20 * 1000)) { // 20 seconds in milliseconds
            console.log(`Cache expired: true`); 
            const data = await this.getFaxlist(); // Fetch new data
            cachedData = { expire_cache: Date.now(), data }; // Create a new cache data object
            // await this.updateCache(cachedData); // Update the cache
            await (await caches.open('fax-cache')).put(new Request('/faxlist'), new Response(JSON.stringify(cachedData), { headers: { 'Content-Type': 'application/json' } }));
        }
        // Set the data source if valid data exists
        if (cachedData.data && cachedData.data.length > 0) {
            this.SendFaxDataSource = this.dataSourceBuilder.create(cachedData.data.map(item => ({ data: item })));
        } 

    } catch (error) {
        console.error('Error handling fax list cache:', error);
    }
}

