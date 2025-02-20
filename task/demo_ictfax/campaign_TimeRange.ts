  ngOnInit(): void {
    this.route.params.subscribe(params => {
      this.campaign_id = +params['id'];
      const test_url = this.router.url.split('/');
      const lastsegment = test_url[test_url.length - 1];
      if (lastsegment === 'new') {
        return null;
      } else {
        return this.campaign_service.get_CampaignData(this.campaign_id).then(data => {
          this.campaign = data;
        });
      }
    });

this.campaign_TimeRange();
    // const time_set = new Date();
    // this.startDateStr = time_set.toISOString().split('T')[0];
    // this.startTimeStr = this.formatTime(time_set);
    // this.endDateStr = this.startDateStr;
    // this.endTimeStr = this.formatTime(new Date(time_set.getTime() + 3600000)); // +1 hour
 


    this.getDocumentlist();
    this.getGrouplist();

    this.document.quality = 'standard';

    this.uploader.onBeforeUploadItem = (item) => {
      item.method = 'POST';
      item.url = this.URL;
      item.withCredentials = false;
    };
    
    this.uploader.onAfterAddingFile = (response: any) => {
      console.log(response);
      this.file = response;
      if (response.file.type == 'application/pdf' || response.file.type == 'image/png' || response.file.type == 'image/jpg' || response.file.type == 'image/jpeg' || response.file.type == 'image/tiff' || response.file.type == 'image/tif' || response.file.type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || response.file.type == 'application/msword' || response.file.type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation' || response.file.type == 'application/vnd.ms-powerpoint' || response.file.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || response.file.type == 'application/vnd.ms-excel' || response.file.type == 'application/vnd.oasis.opendocument.text' || response.file.type == 'application/vnd.oasis.opendocument.presentation' ||  response.file.type == 'application/vnd.oasis.opendocument.spreadsheet') {
        
      }
      else {
        this.unsupportedErr = true;
        this.uploader.removeFromQueue(response);
        setTimeout(() => {
          this.unsupportedErr = false;
        }, 2000);
      }
    };


    const authHeader = this.app_service.upload_Header;
    const uploadOptions = <FileUploaderOptions>{headers : authHeader};
    this.uploader.setOptions(uploadOptions);
    this.uploader.onSuccessItem = (item: any, response: any, status: any, headers: any) => {
    };

    this.uploader.onCompleteAll = () => {
      console.log('complete');
      this.file_sent = true;
      this.file_sending = false;
      this.modalRef.close();
      this.getDocumentlist();
    };
  }
  
//campaign-TimeRange control function
private campaign_TimeRange(): void {
  const now = new Date();
  const end = new Date(now.getTime() + 3600000);

  this.startDateStr = now.toISOString().split('T')[0];
  this.startTimeStr = this.formatTime(now);
  this.endDateStr = end.toISOString().split('T')[0];
  this.endTimeStr = this.formatTime(end);

  this.campaign.startDate = new Date(`${this.startDateStr}T${this.startTimeStr}`);
  this.campaign.endDate = new Date(`${this.endDateStr}T${this.endTimeStr}`);
}


addSendDocument(): void {
  this.campaign_TimeRange();
  this.checkFields("new");
  if (this.errorText.length === 0) {
    this.campaign_service.add_senddocument(this.documentProgram).then(response => {
      const program_id = response;
      this.campaign.program_id = program_id;
      this.addCampaign();
    });
  }else{
        this.errorHandler(true, this.errorText);
      }
}

  updateCampaign(): void {
    // const localStart = new Date(`${this.startDateStr}T${this.startTimeStr}`);
    // const localEnd = new Date(`${this.endDateStr}T${this.endTimeStr}`);
    
    // this.campaign.startDate = this.toUTC(localStart);
    // this.campaign.endDate = this.toUTC(localEnd);
    this.campaign_TimeRange();
    this.checkFields();
    if (this.errorText.length === 0) {
      this.campaign_service.add_senddocument(this.documentProgram).then(response => {
        const program_id = response;
        this.campaign.program_id = program_id;
        this.update();
        this.router.navigate(['../../../campaigns'], {relativeTo: this.route});
      });
    }else{
      this.errorHandler(true, this.errorText);
    }
  }
