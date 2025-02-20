<?php



  /**
   * Create a new custom transmission
   *
   * @url POST /transmissions
   */
  public function set($transmission, $data)
  {
   foreach ($data as $key => $value) {
          $transmission->$key = $value;
      }
  }
 public function create($data = array())
 {
     var_dump("Before authorization");       // Debugging statement: Before authorization
     $this->_authorize('transmission_create');
     var_dump("After authorization");       // Debugging statement: After authorization
     if (empty($data['program_id'])) {
         throw new CoreException(412, 'program_id is missing');
     }
     if (empty($data['contact_id'])) {
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
     } else {
         $contact_id = $data['contact_id'];
     }
     var_dump("After contact_id determination");      // Debugging statement: After contact_id is determined
     unset($data['contact_id']);
     if (empty($data['account_id'])) {
         $oAccount = new Account(Account::USER_DEFAULT);
         $account_id = $oAccount->account_id;
     } else {
         $account_id = $data['account_id'];
     }
     var_dump("After account_id determination");      // Debugging statement: After account_id is determined
     unset($data['account_id']);
     $direction = empty($data['direction']) ? Transmission::OUTBOUND : $data['direction'];
     unset($data['direction']);
     $oProgram = Program::load($data['program_id']);
     $oTransmission = $oProgram->transmission_create($contact_id, $account_id, $direction);
     var_dump("After transmission_create");      // Debugging statement: After transmission_create
     $this->set($oTransmission, $data);
     var_dump("Before save operation");      // Debugging statement: Before the save operation
     if ($oTransmission->save()) {
         var_dump("Transmission saved successfully");           // Debugging statement: After a successful save
         return $oTransmission->transmission_id;
     } else {
         var_dump("Transmission save failed");           // Debugging statement: If the save operation fails
         throw new CoreException(417, 'Transmission creation failed');
     }
 }


http://66.135.10.179/api/bulk/documents?after=1695106273&before=1695882798


 /**
  * Export bulk fax details in ZIP format for download.
  *
  * @url GET /bulk/download
  */
 public function bulk_download($query = array() )
 {
   // $this->_authorize('document_read'); =>No need for authorization
   global $path_data;
   $filter = (array)$query;
   $listDocument = $this->list_view($filter);
   mkdir("$path_data/bulk_document", 0777, true);
   $zipFileName = 'bulk_zip_document' . time() . '.zip';
   $zipFilePath = $path_data . DIRECTORY_SEPARATOR . 'bulk_document' . DIRECTORY_SEPARATOR . $zipFileName;
   $zip = new ZipArchive();
   if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
     throw new CoreException(500, 'Failed to create ZIP archive.');
   }
   if ($listDocument) {
     foreach ($listDocument as $aValue) {
       $oDocument = new Document($aValue['document_id']);
       if ($oDocument->document_id) {
         $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);
         $documentFileName = basename($pdf_file);
         $zip->addFile($pdf_file, $documentFileName);
       }
     }
   }
   $zip->close();
   header("Access-Control-Allow-Origin: *");
   if (file_exists($zipFilePath)) {
       header('Content-Type: application/zip');
       header('Content-Disposition: attachment; filename="bulk_download.zip"');
       header('Content-Length: ' . filesize($zipFilePath));
       readfile($zipFilePath);
       $files = glob($path_data . '/*'); // remove files
       foreach ($files as $file) {
         if (is_file($file)) {
           unlink($file);
         }
       }
     } else {
       throw new CoreException(404, 'Bulk document download archive not found.');
   }
 }

/**
   * Export bulk fax details in ZIP format for download.
   *
   * @url GET /bulk/documents
   */
  public function bulk_download($query = array())
  {
       $startTimestamp = 1693629910;
       $endTimestamp = 1695106273;

      //  $startTimestamp = strtotime('2023-09-01');
      //   $endTimestamp = strtotime('2023-09-30');

      $this->_authorize('document_read');
      // $searchFilter = (array) $query;
      // var_dump($searchFilter);
      $tempDir = '/usr/ictcore/data/bulk_document';
      // mkdir($tempDir, 0777, true);
        if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true);
}
      $zipFileName = 'bulk_zip_document' . time() . '.zip';
      $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
      $zip = new ZipArchive();
      // $zip->open($zipFilePath, ZipArchive::CREATE);
          if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
        throw new CoreException(500, 'Failed to create ZIP archive.');
    }
      // $documentIds = Document::search($searchFilter);
      $documentIds = Document::search([
      'date_created' => $startTimestamp,
      'date_created' => $endTimestamp,
    ]);
    // var_dump($documentIds);exit;
   $filteredDocuments = array();
      foreach ($documentIds as $documentId) {
          $oDocument = new Document($documentId['document_id']);
          if ($oDocument->document_id) {
          $documentTimestamp = (int) $documentId['date_created'];
             // Check if the document falls within the specified date range or no date range provided
             if ((!$startTimestamp || !$endTimestamp) || ($documentTimestamp >= $startTimestamp && $documentTimestamp <= $endTimestamp)) {
            $filteredDocuments[] = $documentId;
            // var_dump($filteredDocuments);exit;

              $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);
                  $documentFileName = basename($pdf_file);
                  $zip->addFile($pdf_file, $documentFileName);
              }  }  }
      $zip->close();
      //tempory add headers testing
      header("Access-Control-Allow-Origin: *");
      if (file_exists($zipFilePath)) {
          header('Content-Type: application/zip');
          header('Content-Disposition: attachment; filename="bulk_download.zip"');
          header('Content-Length: ' . filesize($zipFilePath));
          readfile($zipFilePath);
          $files = glob($tempDir . '/*'); // remove files
          foreach ($files as $file) {
              if (is_file($file)) {
                  unlink($file);
              }   }   } else {
          throw new CoreException(404, 'Bulk document download archive not found.');
      }
  }



/**
 * Export bulk fax details in ZIP format for download.
 *
 * @url GET /bulk/documents/$startDate/$endDate
 */
public function bulk_downloads($startDate, $endDate)
{
    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime($endDate);
    if ($startTimestamp === false || $endTimestamp === false) {
        throw new CoreException(400, 'Invalid date format. Please use YYYY-MM-DD.');
    }
    $this->_authorize('document_read');
    $tempDir = '/usr/ictcore/data/bulk_document';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }
    $zipFileName = 'bulk_zip_document' . time() . '.zip';
    $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
    $zip = new ZipArchive();
    if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
        throw new CoreException(500, 'Failed to create ZIP archive.');
    }
    $documentIds = Document::search([
        'before' => date('0000-00-00', $endTimestamp),
        'after' => date('0000-00-00', $startTimestamp),
    ]);
    $filteredDocuments = array();
    foreach ($documentIds as $documentId) {
        $oDocument = new Document($documentId['document_id']);
        if ($oDocument->document_id) {
            $documentTimestamp = strtotime($documentId['date_created']);
            if (!$startTimestamp || !$endTimestamp || ($documentTimestamp >= $startTimestamp && $documentTimestamp <= $endTimestamp)) {
                $filteredDocuments[] = $documentId;
                $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);
                $documentFileName = basename($pdf_file);
                $zip->addFile($pdf_file, $documentFileName);
            }
        }
    }
    $zip->close();
    header("Access-Control-Allow-Origin: *");
    if (file_exists($zipFilePath)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="bulk_download.zip"');
        header('Content-Length: ' . filesize($zipFilePath));
        readfile($zipFilePath);
        $files = glob($tempDir . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    } else {
        throw new CoreException(404, 'Bulk document download archive not found.');
    }
}




 /**
* Export bulk fax details in ZIP and CSV format for download.
*
* @url GET /bulk/csv
*/
public function export_csv($query = array())
{
 $this->_authorize('document_read');
 $searchFilter = (array) $query;
 // $startDate = isset($query['1692430493']) ? $query['Y-m-d H:i:s'] : null;
 // $endDate = isset($query['Y-m-d H:i:s']) ? $query['Y-m-d H:i:s'] : null;

 // // Check if both start_date and end_date are provided and valid
 // if ($startDate === null || $endDate === null || strtotime($startDate) === false || strtotime($endDate) === false) {
 //     // Display an error message and exit
 //     echo "Please select valid start_date and end_date.";
 //     return;
 // }

 // $searchFilter = array(
 //     'start_date' => $startDate,
 //     'end_date' => $endDate,
 // );
 $documentIds = Document::search($searchFilter);
 // var_dump($documentIds);
 // exit;
 // var_dump($documentIds);
if (empty($documentIds)) {
   throw new CoreException(404, 'No documents found based on the provided filters.');
}
// $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bulk fax' . '.csv';
$tempDir = '/usr/ictcore/data/csv_document'; // Set the new directory path
$handle = fopen($tempDir, 'w');
if (!$handle) {
   throw new CoreException(500, 'Unable to open file for CSV export.');
}
$header_row = '"Document ID","Doc Name","Doc Type","Doc File_name","Doc description"' . "\n";
fwrite($handle, $header_row);
foreach ($documentIds as $documentId) {
   $oDocument = new Document($documentId['document_id']);
   // var_dump($oDocument);
   if (!$oDocument->document_id) {
       continue; // Skip invalid document IDs
   }
   $documentData = [
       $oDocument->document_id,
       $oDocument->name,
       $oDocument->type,
       $oDocument->file_name,
       $oDocument->description,
     ];
   $document_row = '"' . implode('","', $documentData) . '"' . "\n";
   fwrite($handle, $document_row);
}
fclose($handle);
if (file_exists($tempDir)) {
   $csvFileInfo = new SplFileInfo($tempDir);
   return $csvFileInfo;
} else {
 throw  CoreException(404, 'CSV export file not found.');
}
}
/**
* Export bulk fax details in ZIP format for download.
*
* @url GET /bulk/documents/$start_date/$end_date
*/
public function bulk_download($start_date, $end_date)
{
   // Convert $start_date and $end_date to timestamps if needed.
   $startTimestamp = strtotime($start_date);
   $endTimestamp = strtotime($end_date);

   // You can adjust the date range here if needed.
   // For example, if you want to include the entire day for the end date:
   // Adjust the end date to the end of the day (23:59:59).
   $endTimestamp = strtotime('23:59:59', $endTimestamp);

   // Authorize the user.
   $this->_authorize('document_read');

   // Create a temporary directory for the ZIP file.
   $tempDir = '/usr/ictcore/data/bulk_document';
   if (!file_exists($tempDir)) {
       mkdir($tempDir, 0777, true);
   }

   // Generate the ZIP file.
   $zipFilePath = $this->generateZipFile($tempDir, $startTimestamp, $endTimestamp);

   // Send the ZIP file to the user for download.
   $this->sendZipFile($zipFilePath);
}

/**
* Generate a ZIP file containing filtered documents.
*
* @param string $tempDir
* @param int $startTimestamp
* @param int $endTimestamp
* @return string
* @throws CoreException
*/
private function generateZipFile($tempDir, $startTimestamp, $endTimestamp)
{
   $zipFileName = 'bulk_zip_document' . time() . '.zip';
   $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
   $zip = new ZipArchive();

   if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
       throw new CoreException(500, 'Failed to create ZIP archive.');
   }

   // Fetch and filter document IDs.
   $documentIds = $this->fetchAndFilterDocumentIds($startTimestamp, $endTimestamp);

   // Add filtered documents to the ZIP file.
   foreach ($documentIds as $documentId) {
       $pdf_file = $this->generatePdfFile($documentId);
       $documentFileName = basename($pdf_file);
       $zip->addFile($pdf_file, $documentFileName);
   }

   $zip->close();
   return $zipFilePath;
}



/**
  * Export bulk fax details in ZIP format for download.
  *
  * @url GET /bulk/documents
  */
 public function bulk_download($query = array())
 {
 // $startTimestamp = strtotime($startDateTime);
 // $endTimestamp = strtotime($endDateTime);


 // $startTimestamp = strtotime('2023-01-01'); // Replace with your actual start timestamp
     // $endTimestamp = strtotime('2023-09-26');   // Replace with your actual end timestamp

     // $startTimestamp = strtotime('2023-09-01'); // Replace with your actual start timestamp
     // $endTimestamp = strtotime('2023-09-30');   // Replace with your actual end timestamp



     // $startTimestamp = strtotime('2023-09-01'); // Replace with your actual start timestamp
     // $endTimestamp = strtotime('2023-09-30');   // Replace with your actual end timestamp

     //  $startTimestamp = 1693629910;
     //  $endTimestamp = 1695106273;
     // Replace these with your actual default start and end timestamps
$defaultStartTimestamp = strtotime('2023-09-01');
$defaultEndTimestamp = strtotime('2023-09-30');

// Check if 'date_created' exists in $searchFilter and use it if available
if (isset($searchFilter['date_created'])) {
   $startTimestamp = strtotime($searchFilter['date_created']);
   $endTimestamp = strtotime($searchFilter['date_created']);
} else {
   // If 'date_created' is not set in $searchFilter, use the default values
   $startTimestamp = $defaultStartTimestamp;
   $endTimestamp = $defaultEndTimestamp;
}


     $startTimestamp = isset($searchFilter['date_created']) ? $searchFilter['date_created'] : null;
     $endTimestamp = isset($searchFilter['date_created']) ? $searchFilter['date_created'] : null;

     $start_converted = strtotime($startTimestamp);
     $end_converted = strtotime($endTimestamp);

     $this->_authorize('document_read');
     $searchFilter = (array) $query;
     $tempDir = '/usr/ictcore/data/bulk_document';
     // mkdir($tempDir, 0777, true);
       if (!file_exists($tempDir)) {
   mkdir($tempDir, 0777, true);
}
     $zipFileName = 'bulk_zip_document' . time() . '.zip';
     $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
     $zip = new ZipArchive();

     // $zip->open($zipFilePath, ZipArchive::CREATE);

         if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
       throw new CoreException(500, 'Failed to create ZIP archive.');
   }

     // $documentIds = Document::search($searchFilter);
     $documentIds = Document::search([
     'date_created' => $start_converted,
     'date_created' => $end_converted,
   ]);
  $filteredDocuments = array();
     foreach ($documentIds as $documentId) {
         $oDocument = new Document($documentId['document_id']);
         if ($oDocument->document_id) {
         $documentTimestamp = (int) $documentId['date_created'];
            // Check if the document falls within the specified date range or no date range provided
            if ((!$start_converted || !$end_converted) || ($documentTimestamp >= $start_converted && $documentTimestamp <= $end_converted)) {
          // Check if either start date or end date is not provided and defoult set
         // if ((!$startTimestamp || !$endTimestamp) || ($documentTimestamp >= $startTimestamp && $documentTimestamp <= $endTimestamp)) {
         // if (($startTimestamp && $endTimestamp) && ($documentTimestamp >= $startTimestamp && $documentTimestamp <= $endTimestamp)) {
          // if ($documentTimestamp >= $startTimestamp && $documentTimestamp <= $endTimestamp) {
           $filteredDocuments[] = $documentId;
           // var_dump($filteredDocuments);exit;
             $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);
                 $documentFileName = basename($pdf_file);
                 $zip->addFile($pdf_file, $documentFileName);
             }  }  }
     $zip->close();
     //tempory add headers testing
     header("Access-Control-Allow-Origin: *");
     if (file_exists($zipFilePath)) {
         header('Content-Type: application/zip');
         header('Content-Disposition: attachment; filename="bulk_download.zip"');
         header('Content-Length: ' . filesize($zipFilePath));
         readfile($zipFilePath);
         $files = glob($tempDir . '/*'); // remove files
         foreach ($files as $file) {
             if (is_file($file)) {
                 unlink($file);
             }   }   } else {
         throw new CoreException(404, 'Bulk document download archive not found.');
     }
 }








public function bulk_download($query = array())
{
   $this->_authorize('document_read');
   $searchFilter = (array) $query;
   $tempDir = '/usr/ictcore/data/bulk_document';
   mkdir($tempDir, 0777, true);
   $zipFileName = 'bulk_zip_document' . date('YmdHis') . '.zip'; // Include current date and time in the filename
   $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
   $zip = new ZipArchive();
   $zip->open($zipFilePath, ZipArchive::CREATE);

   // Get the start and end dates from the query
   $startDate = isset($searchFilter['start_date']) ? $searchFilter['start_date'] : null;
   $endDate = isset($searchFilter['end_date']) ? $searchFilter['end_date'] : null;

   $documentIds = Document::search($searchFilter);
   foreach ($documentIds as $documentId) {
       $oDocument = new Document($documentId['document_id']);
       if ($oDocument->document_id) {
           $documentDate = date('Y-m-d H:i:s', $oDocument->date_created);

           // Check if the document date is within the specified range
           if ($startDate && $endDate) {
               if ($documentDate >= $startDate && $documentDate <= $endDate) {
                   $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);
                   $documentFileName = basename($pdf_file);
                   $zip->addFile($pdf_file, $documentFileName);
               }
           } elseif ($startDate) {
               if ($documentDate >= $startDate) {
                   $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);
                   $documentFileName = basename($pdf_file);
                   $zip->addFile($pdf_file, $documentFileName);
               }
           } elseif ($endDate) {
               if ($documentDate <= $endDate) {
                   $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);
                   $documentFileName = basename($pdf_file);
                   $zip->addFile($pdf_file, $documentFileName);
               }
           } else {
               // If neither start date nor end date is provided, include all documents
               $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);
               $documentFileName = basename($pdf_file);
               $zip->addFile($pdf_file, $documentFileName);
           }
       }
   }
   $zip->close();
   header("Access-Control-Allow-Origin: *");
   if (file_exists($zipFilePath)) {
       header('Content-Type: application/zip');
       header('Content-Disposition: attachment; filename="bulk_download.zip"');
       header('Content-Length: ' . filesize($zipFilePath));
       readfile($zipFilePath);
       $files = glob($tempDir . '/*');
       foreach ($files as $file) {
           if (is_file($file)) {
               unlink($file);
           }
       }
   } else {
       throw new CoreException(404, 'Bulk document download archive not found.');
   }
}









/**
* Export bulk fax details in CSV format for download.
*
* @url GET /transmissions/bulk-sendfax/export/csv
*/
public function download_bulk_fax($direction, $query = array())
{
   $this->_authorize('transmission_read');
   $filter = (array)$query;

   // Implement logic for exporting bulk fax details here

   // Generate a CSV file with fax details
   $csvData = $this->generateBulkFaxCsv($filter, $direction);

   if (!empty($csvData)) {
       // Send the CSV data as a downloadable file
       return $this->sendCsvDownload($csvData, $direction);
   } else {
       throw new CoreException(404, "No records found for bulk download");
   }
}

/**
* Send CSV data as a downloadable file.
*
* @param string $csvData CSV data as a string.
* @param string $direction Direction of the fax (inbound or outbound).
* @return SplFileInfo Downloadable CSV file.
*/
private function sendCsvDownload($csvData, $direction)
{
   // Save CSV data to a temporary file
   $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'export_bulk_transmission_' . $direction . '.csv';
   file_put_contents($file_path, $csvData);

   // Return the CSV file as a SplFileInfo object for download
   return new SplFileInfo($file_path);
}




 /**
 * Export bulk fax details in ZIP and CSV format for download.
 *
 * @url GET /bulk/csv
 */
public function bulk_csv($query = array())
{
    $this->_authorize('document_read');
    $searchFilter = (array) $query; // Retrieve document IDs based on the search filter

    $documentIds = Document::search($searchFilter);

    if (!is_array($documentIds)) { // Check if document IDs are provided as an array
        throw new CoreException(400, 'Invalid input for document_ids.');
    }

    $documentData = array();

    foreach ($documentIds as $documentId) {
        $oDocument = new Document($documentId['document_id']);

        if ($oDocument->document_id) {
            // Add document data to the result array
            $documentData[] = array(
                'document_id' => $oDocument->document_id,
                'name' => $oDocument->name,
                'file_name' => $oDocument->file_name,
                // Add more fields as needed
            );
        }
    }

    // Return the document data as JSON response
    header('Content-Type: application/json');
    echo json_encode($documentData);
    exit;
}

      /**
       * Export bulk fax details in ZIP format for download.
       *
       * @url GET /bulk/documents/json
       */
      public function bulk_downloads($query = array())
      {
          $this->_authorize('document_read');
          $searchFilter = (array) $query; // Retrieve document IDs based on the search filter

          $documentIds = Document::search($searchFilter);

          if (!is_array($documentIds)) { // Check if document IDs are provided as an array
              throw new CoreException(400, 'Invalid input for document_ids.');
          }

          $tempDir = '/usr/ictcore/data/bulk_document'; // Set the new directory path
          if (!file_exists($tempDir)) {
              mkdir($tempDir, 0777, true);
              chmod($tempDir, 0777); // Set write permissions
          }

          $zip = new ZipArchive();
          // var_dump($zip);
          $zipFileName = 'bulk_zip_document' . time() . '.zip';
          $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
          // var_dump($zipFilePath);

          if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
              throw new CoreException(500, 'Failed to create ZIP archive.');
          }

          foreach ($documentIds as $documentId) {
              $oDocument = new Document($documentId['document_id']);

              if ($oDocument->document_id) {
                  $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type);

                  if (file_exists($pdf_file)) {
                      $documentFileName = basename($pdf_file);
                      $zip->addFile($pdf_file, $documentFileName);
                  } else {
                      echo "Document not found for ID: $documentId\n";
                  }
              }
          }

      // ... (Previous code remains the same)

// Close the ZIP archive
$zip->close();

// Check for errors during ZIP archive creation
if ($zip->status !== ZipArchive::ER_OK) {
    $error = $zip->getStatusString();
    // Log or handle the ZIP creation error as needed
    throw new CoreException(500, 'Failed to create ZIP archive: ' . $error);
}

// Send the JSON response
$documentData = array(
    'message' => 'ZIP file is ready for download.',
    // Add other JSON data as needed
);

// Prepare JSON response
$jsonData = json_encode($documentData);

// Send JSON response headers
header('Content-Type: application/json');
header('Content-Disposition: inline');

// Output JSON response
echo $jsonData;

// Send the ZIP file to the client for download
header("Access-Control-Allow-Origin: *");

if (file_exists($zipFilePath)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="bulk_download.zip"');
    header('Content-Length: ' . filesize($zipFilePath));

    // Output the ZIP file
    if (readfile($zipFilePath) === false) {
        // Handle readfile error
        throw new CoreException(500, 'Failed to read ZIP file.');
    }
} else {
    // ZIP archive not found
    throw new CoreException(404, 'Bulk document download archive not found. ZipFilePath: ' . $zipFilePath);
}
      }

      // Add the $data_row to the $data array
      $data[] = $data_row;


      public function csv($query = array(), $account_id)
      {
          $this->_authorize('transmission_read');
          $Filter = (array) $query;
          $Filter['account_id'] = $account_id; // Replace '1' with the desired account ID
          // $transmissionIds = transmission::search($Filter);
          $listtransmission = $this->list_view($Filter);
          // var_dump($transmissionIds);exit;
          if (empty($listtransmission)) {
              throw new CoreException(404, 'No transmissions found based on the provided filters.');
          }
          $tempDir = '/usr/ictcore/data/csv_transmission';
          $handle = fopen($tempDir, 'w');
          if (!$handle) {
              throw CoreException(500, 'Unable to open file for CSV export.');
          }
          $header_row = ["transmission_id", "account_id", "account_username", /* Add other column names here... */];
          fwrite($handle, implode(",", $header_row) . PHP_EOL); // Use implode to create a CSV row
          $csvData = [];
          foreach ($listtransmission as $row) {
            // var_dump($listtransmission);
            // exit;
              $data_row = [
                  $row["transmission_id"],
                  $row["account_id"],
                  $row["account_username"],
                  $row["account_phone"],
                  $row["account_email"],
                  $row["contact_phone"],
                  $row["direction"],
                  // $row["type"],
                  // $row["type"],

              ];
              $csvData[] = $data_row;
          }
          foreach ($csvData as $data_row) {
              fwrite($handle, implode(",", $data_row) . PHP_EOL);
          }
          fclose($handle);
          if (file_exists($tempDir)) {
              $csvFileInfo = new SplFileInfo($tempDir);
              return $csvFileInfo;
          } else {
              throw new CoreException(404, 'CSV export file not found.');
          }
      }



      public function receive($query = array())
{
    $this->_authorize('transmission_read');
    $Filter = (array) $query;
    // $Filter['account_id'] = $account_id; // Replace '1' with the desired account ID
    $Filter['account_id'] = '52'; // Replace '1' with the desired account ID
    // $transmissionIds = transmission::search($Filter);
    $listtransmission = $this->list_view($Filter);
    // var_dump($listtransmission);

    $response = []; // Initialize an empty array to hold the response data

    foreach ($listtransmission as $row) {
      // var_dump($listtransmission);
      // if (isset($row["type"])) {
        $data = []; // Create an empty array to store the extracted data

    $data_row = [
        'transmission_id' => $row["transmission_id"],
        'account_id' => $row["account_id"],
        'account_username' => $row["account_username"],
        'account_type' => $row["account_type"],
        'account_phone' => $row["account_phone"],
        'account_email' => $row["account_email"],
        'user_id' => $row["user_id"],
        'username' => $row["username"],
        'contact_id' => $row["contact_id"],
        'contact_phone' => $row["contact_phone"],
        'contact_email' => $row["contact_email"],
        'status' => $row["status"],
        'response' => $row["response"],
        'notify' => $row["notify"],
        'direction' => $row["direction"],
        'last_run' => $row["last_run"],
        'account_account_id' => $row["account"]["account_id"],
        'account_type' => $row["account"]["type"],
        'account_username' => $row["account"]["username"],
        'account_first_name' => $row["account"]["first_name"],
        'account_last_name' => $row["account"]["last_name"],
        'account_phone' => $row["account"]["phone"],
        'account_email' => $row["account"]["email"],
        'account_created_by' => $row["account"]["created_by"],
        'contact_contact_id' => $row["contact"]["contact_id"],
        'contact_first_name' => $row["contact"]["first_name"],
        'contact_last_name' => $row["contact"]["last_name"],
        'contact_phone' => $row["contact"]["phone"],
        'contact_email' => $row["contact"]["email"],
    ];

    // Add the $data_row to the $data array
    $data[] = $data_row;


// // Now, the $data array contains all the extracted data with all fields from the provided multi-dimensional array

//         $data_row = [
//             'transmission_id' => $row["transmission_id"],
//             'account_id' => $row["account_id"],
//             'account_id' => $row["account"]["account_id"], // Access "account_id" from the "account" sub-array
//             // 'type' => $row["type"],
//             'type' => $row["account"]["type"], // Access "type" from the "account" sub-array
//             'account_username' => $row["account_username"],
//             'account_phone' => $row["account_phone"],
//             'account_email' => $row["account_email"],
//             'contact_phone' => $row["contact_phone"],
//             'direction' => $row["direction"],
//             // Add more fields as needed
//         ];

      // } else {
      //   echo  "type not found";

      // }

        $response[] = $data_row;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}




  /**
 * Download transmission data in fax format.
 *
 * @url GET /transmissions/fax/tenants
 * @url GET /transmissions/fax/$account_id
 * @url GET /transmissions/fax
 *
 */
public function resive($query = array()){
    $this->_authorize('transmission_read');
    $Filter = (array) $query;
    // $Filter['account_id']['tenant_id'] = [$account_id, $tenant_id];
    $listtransmission = $this->list_view($Filter);
    // var_dump($listtransmission);
    // exit;
    foreach ($listtransmission as $row) {
      // var_dump($listtransmission);exit;
        $data_row = [
          'transmission_id' => $row["transmission_id"],
          'tenant_id' => $row["tenant_id"],
          'program_id' => $row["program_id"],
          'account_id' => $row["account"]["account_id"],

          'document_id' => $row["document"]["document_id"],
          'file_name' => $row["document"]["file_name"],
          'type' => $row["document"]["format"],

          'account_username' => $row["account"]["username"],
            'type' => $row["account"]["type"],
            'username' => $row["username"],
            'direction' => $row["direction"],
            'status' => $row["status"],
            'origin' => $row["origin"],
            'title' =>  $row["title"],
            'account_phone' => $row["account"]["phone"],
            'account_email' => $row["account"]["email"],
            'contact_first_name' => $row["contact"]["first_name"],
            'contact_phone' => $row["contact"]["phone"],
            'contact_email' => $row["contact"]["email"],
        ];
         $return = $data_row;
    }
    return $return;
  }
  //




  /**
 * Download transmission data in fax format.
 *
 * @url GET /transmissions/fax/tenants
 * @url GET /transmissions/fax/$account_id
 * @url GET /transmissions/fax
 *
 */
public function receive($query = array())
{
    $this->_authorize('transmission_read');
    $Filter = (array) $query;
    $listTransmission = $this->list_view($Filter);
    return $listTransmission;
  }

//

  // Check if a specific transmission_id is provided for download
  if (isset($_GET['tenant_id']) && is_numeric($_GET['tenant_id'])) {
    $transmissionIdToDownload = (int)$_GET['tenant_id'];

    foreach ($listTransmission as $transmission) {
      if ($transmission['tenant_id'] == $transmissionIdToDownload) {
        $file_path = $transmission['file_name'];
        $oTransmissionFileName = basename($file_path);
        // var_dump($oTransmissionFileName);exit;
        $zip->addFile($file_path, $oTransmissionFileName);
      }
    }
  }

  $zip->close();


/**
 * Download transmission data in fax document format.
 *
 * @url GET /transmissions/fax/tenants
 * @url GET /receive/fax
 *
 */
public function receive($query = array())
{
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
  $zip = new ZipArchive();
  if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
    throw new CoreException(500, 'Failed to create ZIP archive.');
  }
  foreach ($listTransmission as $transmission) {
    // var_dump($listTransmission);exit;
    $file_path = $transmission['file_name'];
    $oTransmissionFileName = basename($file_path);
    // var_dump($oTransmissionFileName);exit;
    $zip->addFile($file_path, $oTransmissionFileName);
  }
  $zip->close();
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
   * Export CDR
   *
   * @url GET /spools/csv
   * @url GET /cdr/csv
   *
   */
  public function export_csv($query = array())
  {
    $filter = (array)$query;
    $listSpool = $this->list_view($filter);
    if ($listSpool) {
      $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'export_cdr'.'.csv';
      $handle = fopen($file_path, 'w');
      if (!$handle) {
        throw new CoreException(500, "Unable to open file");
      }
      foreach($listSpool as $aValue) {
        $contact_row = '"'.date("Y-m-d h:i:s A", $aValue['time_start']).'","'.date("Y-m-d h:i:s A", $aValue['time_connect']).'","'.date("Y-m-d h:i:s A", $aValue['time_end']).'",'.
                       '"'.$aValue['username'].'","'.$aValue['contact_phone'].'","'.$aValue['direction'].'","'.$aValue['company'].'",'.
                       '"'.$aValue['account_phone'].'","'.$aValue['status'].'","'.$aValue['pages'].'"'."\n";
        fwrite($handle, $contact_row);
      }
      fclose($handle);
      return new SplFileInfo($file_path);
    } else {
      throw new CoreException(404, "File not found");
    }
  }
?>
