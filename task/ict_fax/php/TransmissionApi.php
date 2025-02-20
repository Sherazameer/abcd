<?php

namespace ICT\Core\Api;

/* * ***************************************************************
 * Copyright © 2016 ICT Innovations Pakistan All Rights Reserved   *
 * Developed By: Nasir Iqbal                                       *
 * Website : http://www.ictinnovations.com/                        *
 * Mail : nasir@ictinnovations.com                                 *
 * *************************************************************** */

use ICT\Core\Account;
use ICT\Core\Contact;
use ICT\Core\Api;
use ICT\Core\CoreException;
use ICT\Core\Program;
use ICT\Core\Result;
use ICT\Core\Schedule;
use ICT\Core\Service\Email;
use ICT\Core\Service\Fax;
use ICT\Core\Service\Sms;
use ICT\Core\Service\Voice;
use ICT\Core\Spool;
use ICT\Core\Transmission;
use ICT\Core\Contact_Dnc;
use ZipArchive;

class TransmissionApi extends Api
{
/**
 * Create a new custom transmission
 *
 * @url POST /transmissions
 */
public function create($data = array())
{
    $this->_authorize('transmission_create');

    // Check if program_id is provided
    if (empty($data['program_id'])) {
        throw new CoreException(412, 'program_id is missing');
    }

    // Check if contact_id is present
    if (!empty($data['contact_id'])) {
        $oContact = new Contact($data['contact_id']);
        $phone = $oContact->phone;

        $oContactDnc = new Contact_Dnc();
        $contactDncData = $oContactDnc->search(array('phone' => $phone));

        if (!empty($contactDncData)) {
            // Handle DNC case
            $direction = empty($data['direction']) ? Transmission::OUTBOUND : $data['direction'];
            unset($data['direction']);

            $oProgram = Program::load($data['program_id']);
            $oTransmission = $oProgram->transmission_create($data['contact_id'], $data['account_id'], $direction);
            $oTransmission->status = Transmission::STATUS_FAILED;
            $oTransmission->response = 'DNCNumber(Do Not Allow)';

            if (isset($data['title'])) {
                $oTransmission->title = $data['title'];
            }

            if ($oTransmission->save()) {
                return $oTransmission->transmission_id;
            } else {
                throw new CoreException(417, 'Transmission creation failed');
            }
        }
    } else {
        // Handle the case when contact_id is not present
        if (!empty($data['phone']) || !empty($data['email'])) {
            $oContact = new Contact();
            $oContact->phone = empty($data['phone']) ? null : $data['phone'];
            $oContact->email = empty($data['email']) ? null : $data['email'];
            $oContact->save();
            $contact_id = $oContact->contact_id;
            unset($data['phone']);
            unset($data['email']);
        } else {
            throw new CoreException(412, 'contact is missing');
        }
    }

    // Continue with the rest of the code
    // ...

    // Set account_id
    $account_id = empty($data['account_id']) ? (new Account(Account::USER_DEFAULT))->account_id : $data['account_id'];
    unset($data['account_id']);

    // Set direction
    $direction = empty($data['direction']) ? Transmission::OUTBOUND : $data['direction'];
    unset($data['direction']);

    // Continue with transmission creation
    $oProgram = Program::load($data['program_id']);
    $oTransmission = $oProgram->transmission_create($contact_id, $account_id, $direction);

    // Set additional data
    $this->set($oTransmission, $data);

    // Save the transmission
    if ($oTransmission->save()) {
        return $oTransmission->transmission_id;
    } else {
        throw new CoreException(417, 'Transmission creation failed');
    }
}

  // /**
  //  * Create a new custom transmission
  //  *
  //  * @url POST /transmissions
  //  */
  // public function create($data = array())
  // {
  //   $this->_authorize('transmission_create');
  //   if (empty($data['program_id'])) {
  //       throw new CoreException(412, 'program_id is missing');
  //   }
  //   $oContact = new Contact($data['contact_id']);
  //   $phone = $oContact->phone;

  //   $oContactDnc = new Contact_Dnc();
  //   //contact_dnc number is matched then DNCNumber (Do Not Call) Transmission creation failed
  //   $contactDncData = $oContactDnc->search(array('phone' => $phone));
  //   if (!empty($contactDncData)) {
  //     $direction = empty($data['direction']) ? Transmission::OUTBOUND : $data['direction'];
  //     unset($data['direction']);
  //     $oProgram = Program::load($data['program_id']);
  //     $oTransmission = $oProgram->transmission_create($data['contact_id'], $data['account_id'], $direction);
  //     $oTransmission->status = Transmission::STATUS_FAILED;
  //     $oTransmission->response = 'DNCNumber(Do Not Allow)';
  //     if (isset($data['title'])) {
  //       $oTransmission->title = $data['title'];
  //     }
  //     if ($oTransmission->save()) {
  //       return $oTransmission->transmission_id;
  //     } else {
  //       throw new CoreException(417, 'Transmission creation failed');
  //     }
  //   }
  //   //contact_dnc number is not matched
  //   if (empty($data['contact_id'])) {
  //     if (!empty($data['phone']) || !empty($data['email'])) {
  //       $oContact = new Contact();
  //       $oContact->phone = empty($data['phone']) ? null : $data['phone'];
  //       $oContact->email = empty($data['email']) ? null : $data['email'];
  //       $oContact->save();
  //       $contact_id = $oContact->contact_id;
  //       unset($data['phone']);
  //       unset($data['email']);
  //     } else {
  //       throw new CoreException(412, 'contact is missing');
  //     }
  //   } else {
  //     $contact_id = $data['contact_id'];
  //   }
  //   unset($data['contact_id']);
  //   if (empty($data['account_id'])) {
  //     $oAccount = new Account(Account::USER_DEFAULT);
  //     $account_id = $oAccount->account_id;
  //   } else {
  //     $account_id = $data['account_id'];
  //   }
  //   unset($data['account_id']);
  //   $direction = empty($data['direction']) ? Transmission::OUTBOUND : $data['direction'];
  //   unset($data['direction']);
  //   $oProgram = Program::load($data['program_id']);
  //   $oTransmission = $oProgram->transmission_create($contact_id, $account_id, $direction);
  //   $this->set($oTransmission, $data);
  //   if ($oTransmission->save()) {
  //     return $oTransmission->transmission_id;
  //   } else {
  //     throw  CoreException(417, 'Transmission creation failed');
  //   }
  // }




  //  } else if (Transmission::STATUS_FAILED == $this->status) {
//      throw new CoreException(417, 'Transmission DNCNumber(DO NOT CALL) creation failed');




// public function create($data = array())
// {
//     $this->_authorize('transmission_create');
//     if (empty($data['program_id'])) {
//         throw new CoreException(412, 'program_id is missing');
//     }
//     $oContact = new Contact($data['contact_id']);
//     $phone = $oContact->phone;
//     $oContactDnc = new Contact_Dnc();
//     $contactDncData = $oContactDnc->search(array('phone' => $phone));
//     if (!empty($contactDncData)) {
//         $direction = empty($data['direction']) ? Transmission::OUTBOUND : $data['direction'];
//         unset($data['direction']);
//         $oProgram = Program::load($data['program_id']);
//         $oTransmission = $oProgram->transmission_create($data['contact_id'], $data['account_id'], $data['direction']);
//         $oTransmission->status = Transmission::STATUS_FAILED;
//         $oTransmission->response = 'DNC (Do Not Call) number.';
//         if ($oTransmission->save()) {
//             return $oTransmission->transmission_id;
//         } else {
//             throw new CoreException(417, 'Transmission creation failed');
//         }
//     }
//     if (empty($data['contact_id'])) {
//         if (!empty($data['phone']) || !empty($data['email'])) {
//             $oContact = new Contact();
//             $oContact->phone = empty($data['phone']) ? null : $data['phone'];
//             $oContact->email = empty($data['email']) ? null : $data['email'];
//             $oContact->save();
//             $contact_id = $oContact->contact_id;
//             unset($data['phone']);
//             unset($data['email']);
//         } else {
//             throw new CoreException(412, 'contact is missing');
//         }
//     } else {
//         $contact_id = $data['contact_id'];
//     }
//     unset($data['contact_id']);

//     if (empty($data['account_id'])) {
//         $oAccount = new Account(Account::USER_DEFAULT);
//         $account_id = $oAccount->account_id;
//     } else {
//         $account_id = $data['account_id'];
//     }
//     unset($data['account_id']);

//     $direction = empty($data['direction']) ? Transmission::OUTBOUND : $data['direction'];
//     unset($data['direction']);

//     $oProgram = Program::load($data['program_id']);
//     $oTransmission = $oProgram->transmission_create($contact_id, $account_id, $direction);
//     $this->set($oTransmission, $data);

//     if ($oTransmission->save()) {
//         return $oTransmission->transmission_id;
//     } else {
//         throw  CoreException(417, 'Transmission creation failed');
//     }
// }

  // public function create($data = array())
  // {
  //   $this->_authorize('transmission_create');
  //   if (empty($data['program_id'])) {
  //     throw new CoreException(412, 'program_id is missing');
  //   }
  //   $oContact = new Contact($data['contact_id']);
  //   $phone = $oContact->phone;
  //   // Check if the phone number already exists in Contact_Dnc
  //   $oContactDnc = new Contact_Dnc();
  //   $contactDncData = $oContactDnc->search(array('phone' => $phone));

  //   if (!empty($contactDncData)) {
  //       // If the phone number exists in Contact_Dnc, set the transmission status to 'failed' and provide a reason.
  //       $oProgram = Program::load($data['program_id']);
  //       $oTransmission = $oProgram->transmission_create($data['contact_id'], $data['account_id'], $data['direction']);
  //       $oTransmission->status = Transmission::STATUS_FAILED;
  //       $oTransmission->response = 'DNC (Do Not Call) number.';

  //       if ($oTransmission->save()) {
  //           return $oTransmission->transmission_id;
  //       } else {
  //           throw new CoreException(417, 'Transmission creation failed');
  //       }
  //   }
  //   if (empty($data['contact_id'])) {
  //     if (!empty($data['phone']) || !empty($data['email'])) {
  //       $oContact = new Contact();
  //       $oContact->phone = empty($data['phone']) ? null : $data['phone'];
  //       $oContact->email = empty($data['email']) ? null : $data['email'];
  //       $oContact->save();
  //       $contact_id = $oContact->contact_id;
  //       unset($data['phone']);
  //       unset($data['email']);
  //     } else {
  //       throw new CoreException(412, 'contact is missing');
  //     }
  //   } else {

  //     $contact_id = $data['contact_id'];
  //   }
  //   unset($data['contact_id']);

  //   if (empty($data['account_id'])) {
  //     $oAccount = new Account(Account::USER_DEFAULT);
  //     $account_id = $oAccount->account_id;
  //   } else {
  //     $account_id = $data['account_id'];
  //   }
  //   unset($data['account_id']);

  //   $direction = empty($data['direction']) ? Transmission::OUTBOUND : $data['direction'];
  //   unset($data['direction']);

  //   $oProgram = Program::load($data['program_id']);
  //   $oTransmission = $oProgram->transmission_create($contact_id, $account_id, $direction);
  //   $this->set($oTransmission, $data);

  //   if ($oTransmission->save()) {
  //     return $oTransmission->transmission_id;
  //   } else {
  //     throw new CoreException(417, 'Transmission creation failed');
  //   }
  // }

  /**
   * List all available transmissions
   *
   * @url GET /transmissions
   */
  public function list_view($query = array())
  {
    $this->_authorize('transmission_list');
    $filter  = (array)$query;
    $filter += $this->_authorization_filter();
    $listTransmission = Transmission::search($filter);
    foreach ($listTransmission as $key => $aTransmission) {
      $listAccount = Account::search(array('account_id' => $aTransmission['account_id']));
      $listTransmission[$key]['account'] = array_shift($listAccount);
      $listContact = Contact::search(array('contact_id' => $aTransmission['contact_id']));
      $listTransmission[$key]['contact'] = array_shift($listContact);
    }
    return $listTransmission;
  }
//



  /**
   * Download transmission data in fax document format.
   *
   * @url GET /bulk/filters
   *
   */
  public function receive($query = array())
  {

    $Filter = (array) $query;
    $listTransmission = $this->list_view($Filter);
  //   return $listTransmission;
   // }
    global $path_data;
    $filter = (array)$query;
    $listTransmission = $this->list_view($filter);
    $faxReceiveDir = "$path_data/fax_receive";
    if (!is_dir($faxReceiveDir)) {
      if (!mkdir($faxReceiveDir, 0777, true)) {
        throw new CoreException(500, 'Failed to create fax_receive directory.');
        }
    }
    $zipFileName = 'bulk_fax_receive' . time() . '.zip';
    $zipFilePath = $path_data . DIRECTORY_SEPARATOR . 'fax_receive' . DIRECTORY_SEPARATOR . $zipFileName;
    foreach ($listTransmission as $transmission) {
      $zip = new ZipArchive(); // Create a new ZipArchive object for each file
      if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
          throw new CoreException(500, 'Failed to create ZIP archive.');
      }
      $file_path = $transmission['file_name'];
      if (file_exists($file_path)) {
          $oTransmissionFileName = basename($file_path);
          $zip->addFile($file_path, $oTransmissionFileName);
          $zip->close();
      } else {
          throw new CoreException(404, 'File not found: ' . $file_path);
      }
  }
    header("Access-Control-Allow-Origin: *");
    if (file_exists($zipFilePath)) {
      header('Content-Type: application/zip');
      header('Content-Disposition: attachment; filename="fax_receive.zip"');
      header('Content-Length: ' . filesize($zipFilePath));
      readfile($zipFilePath);
    } else {
      throw new CoreException(404, 'fax_receive download archive not found.');
    }
  }



  /**
   * List all available calls
   *
   * @url GET /calls
   */
  public function call_list($data = array())
  {
    $data['service_flag'] = Voice::SERVICE_FLAG;
    return $this->list_view($data);
  }

  /**
   * List all available faxs
   *
   * @url GET /faxes
   */
  public function fax_list($data = array())
  {
    $data['service_flag'] = Fax::SERVICE_FLAG;
    return $this->list_view($data);
  }

  /**
   * List all available SMS messages
   *
   * @url GET /smses
   */
  public function sms_list($data = array())
  {
    $data['service_flag'] = Sms::SERVICE_FLAG;
    return $this->list_view($data);
  }

  /**
   * List all available emails
   *
   * @url GET /emails
   */
  public function email_list($data = array())
  {
    $data['service_flag'] = Email::SERVICE_FLAG;
    return $this->list_view($data);
  }

  /**
   * Gets the transmission by id
   *
   * @url GET /transmissions/$transmission_id
   */
  public function read($transmission_id)
  {
    $this->_authorize('transmission_read');

    $oTransmission = new Transmission($transmission_id);
    return $oTransmission;
  }

  /**
   * Update existing transmission
   *
   * @url PUT /transmissions/$transmission_id
   */
   public function update($transmission_id, $data = array())
   {
    $this->_authorize('transmission_update');

    $oTransmission = new Transmission($transmission_id);
    $this->set($oTransmission, $data);

    if ($oTransmission->save()) {
      return $oTransmission;
    } else {
      throw new CoreException(417, 'Transmission update failed');
    }
  }

  /**
   * Delete a transmission
   *
   * @url DELETE /transmissions/$transmission_id
   */
  public function remove($transmission_id)
  {
    $this->_authorize('transmission_delete');

    $oTransmission = new Transmission($transmission_id);

    $result = $oTransmission->delete();
    if ($result) {
      return $result;
    } else {
      throw new CoreException(417, 'Transmission delete failed');
    }
  }

  /**
   * Send already saved transmission
   *
   * @url POST /transmissions/$transmission_id/send
   * @url POST /transmissions/$transmission_id/retry
   * 
   * TODO / TBD: Replace it with POST or PUT as GET is not allowed to modify data
   */
  public function send($transmission_id)
  {
    $this->_authorize('transmission_send');

    $oTransmission = new Transmission($transmission_id);
    return $oTransmission->send();
  }

  /**
   * Schedule transmission 
   *
   * @url PUT /transmissions/$transmission_id/schedule
   */
  public function schedule_create($transmission_id, $data = array())
  {
    $this->_authorize('transmission_send');
    $this->_authorize('task_create');

    $oSchedule = new Schedule();
    $this->set($oSchedule, $data);

    $oTransmission = new Transmission($transmission_id);
    $oSchedule->type = 'transmission';
    $oSchedule->action = 'send';
    $oSchedule->data = $oTransmission->transmission_id;
    $oSchedule->account_id = $oTransmission->account_id;
    $oSchedule->save();

    return $oSchedule->task_id;
  }

  /**
   * Cancel transmission schedule
   *
   * @url DELETE /transmissions/$transmission_id/schedule
   */
  public function schedule_cancel($transmission_id)
  {
    $this->_authorize('transmission_update');
    $this->_authorize('task_delete');

    $oTransmission = new Transmission($transmission_id);
    return $oTransmission->task_cancel();
  }

  /**
   * Create new transmission by cloning existing one
   *
   * @url GET /transmissions/$transmission_id/clone
   * 
   * TBD / TODO: replace GET method with POST, as it voilate REST GET no data change rule
   */
  public function create_clone($transmission_id)
  {
    $this->_authorize('transmission_read');
    $this->_authorize('transmission_create');

    $oldTransmission = new Transmission($transmission_id);
    $newTransmission = clone $oldTransmission;
    if ($newTransmission->save()) {
      return $newTransmission->transmission_id;
    } else {
      throw new CoreException(417, 'Transmission cloning failed');
    }
  }

  /**
   * Get transmission status
   *
   * @url GET /transmissions/$transmission_id/status
   */
  public function status($transmission_id)
  {
    $this->_authorize('transmission_read');

    $oTransmission = new Transmission($transmission_id);
    return $oTransmission->status;
  }

  /**
   * Get transmission results
   *
   * @url GET /transmissions/$transmission_id/logs
   * @url GET /transmissions/$transmission_id/detail
   * @url GET /transmissions/$transmission_id/spools
   */
  public function detail($transmission_id, $query = array())
  {
    $this->_authorize('transmission_read');
    $this->_authorize('spool_read');

    $filter  = (array)$query;
    $filter += array('transmission_id' => $transmission_id);
    return Spool::search($filter);
  }

  /**
   * Get transmission details
   *
   * @url GET /transmissions/$transmission_id/result
   * @url GET /transmissions/$transmission_id/results
   */
  public function result_recent($transmission_id, $query = array())
  {
    $this->_authorize('result_read');

    $listSpool = $this->detail($transmission_id);
    $aSpool    = end($listSpool);

    $filter  = (array)$query;
    $filter += array('spool_id' => $aSpool['spool_id']);

    return Result::search($filter);
  }

}
