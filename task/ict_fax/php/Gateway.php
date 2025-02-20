  <?php
  public function authorize(Request $oRequest, Dialplan $oDialplan)
  {
    if ($oDialplan->context == 'internal') {
      $account = $oRequest->source;
      $contact = $oRequest->destination;
    } else {
      $account = $oRequest->destination;
      $contact = $oRequest->source;
    }

    // search fo existing account and contact
    $oGateway = Gateway::load($oDialplan->gateway_flag);
    $oAccount = Gateway::locate_account($account);
     Corelog::log("----------->" . print_r($oAccount, true) . "<-----------", Corelog::ERROR);
    if ($oAccount) {
      $oContact = $oGateway->locate_contact($contact);
      if ($oContact === false) {
        $oContact = new Contact();
        $contactField = $oGateway::CONTACT_FIELD;
        $oContact->$contactField = $contact;
      }
      return array('account' => $oAccount, 'contact' => $oContact);
    } else {
      return false; // no account found
    }
  }

