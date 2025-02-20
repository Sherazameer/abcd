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

  async getFaxlist() {
    try {
      const data = await this.sendfax_service.get_OutFaxTransmissionList();
      this.aSendFax = data.sort((a, b) => b.transmission_id - a.transmission_id);
      this.length = data.length;
      data.forEach(element => {
        if (element.contact_phone == null) {
          element.contact_phone = 'N/A';
        }
      });
      // this.SendFaxDataSource = this.dataSourceBuilder.create(data.map(item => ({ data: item })));
      return data; // Return the fetched data
    } catch (error) {
      console.error('Error fetching and processing fax list:', error);
      return [];
    }
  }
