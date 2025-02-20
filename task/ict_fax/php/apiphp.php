
  /**
   * Export bulk fax details in ZIP format for download.
   *
   * @url GET /bulk/documents
   */
public function bulk_download($query = array())
{


// $startDateTime = "2023-01-01 12:18:30";
// $endDateTime = "2023-09-26 14:51:13";

// // Convert start and end date strings to timestamps
// $startTimestamp = strtotime($startDateTime);
// $endTimestamp = strtotime($endDateTime);

$startTimestamp = 1693629910;  ///2023-01-01 12:18:30
    $endTimestamp = 1695106273;  /// "2023-09-26 14:51:13"

    // // Get the start and end dates from the query
    // $startTimestamp = isset($searchFilter['start_date']) ? $searchFilter['start_date'] : null;
    // $endTimestamp = isset($searchFilter['end_date']) ? $searchFilter['end_date'] : null;

    $this->_authorize('document_read');
    $searchFilter = (array) $query;
    $tempDir = '/usr/ictcore/data/bulk_document';
    mkdir($tempDir, 0777, true);
    $zipFileName = 'bulk_zip_document' . time() . '.zip';
    $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
    $zip = new ZipArchive();
    $zip->open($zipFilePath, ZipArchive::CREATE);
    // $documentIds = Document::search($searchFilter);
  $documentIds = Document::search([
    'date_created' => $startTimestamp,
    'date_created' => $endTimestamp,
]);
// var_dump($documentIds);
// exit;
 $filteredDocuments = array();
    foreach ($documentIds as $documentId) {
        $oDocument = new Document($documentId['document_id']);
        if ($oDocument->document_id) {
        $documentTimestamp = (int) $documentId['date_created'];

        if (($startTimestamp && $endTimestamp) && ($documentTimestamp >= $startTimestamp && $documentTimestamp <= $endTimestamp)) {
        // if ($documentTimestamp >= $startTimestamp && $documentTimestamp <= $endTimestamp) {
            $filteredDocuments[] = $documentId;
            // var_dump($filteredDocuments);
            // exit;
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

//  public function bulk_download($query = array())
//  {
//   $this->_authorize('document_read');
//   $searchFilter = (array) $query;      // Retrieve document IDs based on the search filter



//   // $startDate = isset($query['start_date']) ? $query['start_date'] : null;
//   // $endDate = isset($query['end_date']) ? $query['end_date'] : null;

// // // Check if both start_date and end_date are provided and valid
// // if ($startDate === null || $endDate === null || strtotime($startDate) === false || strtotime($endDate) === false) {
// //     // Display an error message and exit
// //     echo "Please select valid start_date and end_date.";
// //     return;
// // }

// // $searchFilter = array(
// //     'start_date' => $startDate,
// //     'end_date' => $endDate,
// // );

// $documentIds = Document::search($searchFilter);
// // var_dump($documentIds);    //data is ok
// if (!extension_loaded('zip')) {      // Check if the ZIP extension is available
//     throw new CoreException(500, 'ZIP extension not found.');
// }
// if (!is_array($documentIds)) {      // Check if document IDs are provided as an array
//     throw new CoreException(400, 'Invalid input for document_ids.');
//   }

//   $tempDir = '/usr/ictcore/data/bulk_document'; // Set the new directory path
//   if (!file_exists($tempDir)) {
//     mkdir($tempDir, 0777, true);
//     chmod($tempDir, 0777); // Set write permissions
// }
//     $zip = new ZipArchive();
//     // var_dump($zip);
//     $zipFileName = 'bulk_zip_document' . time() . '.zip';
//     $zipFilePath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;
//     // var_dump($zipFilePath);    //string(56) "/tmp/bulk_document/bulk_zip_file_download_1694688470.zip"  uniq file
//     // exit;
//     // data is ok
//     if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
//         throw new CoreException(500, 'Failed to create ZIP archive.');
//     }
//     foreach ($documentIds as $documentId) {
//       // $oDocument = new Document($documentId);
//       $oDocument = new Document($documentId['document_id']);
//       // var_dump($oDocument);
//       // exit;
//       if (!$oDocument->document_id) {
//           continue; // Skip invalid document IDs
//       }
//       $pdf_file = $oDocument->create_pdf($oDocument->file_name, $oDocument->type); // Generate the PDF file for the document
//       // var_dump($pdf_file);
//       // exit;
//       if (file_exists($pdf_file)) {
//           // var_dump($pdf_file);
//           $documentFileName = basename($pdf_file);
//           $zip->addFile($pdf_file, $documentFileName);
//         } else {

//           echo "Document not found for ID: $documentId\n";      // Print a debug message for missing documents
//         }
//       }
//       $zip->close();

//       // }
//       header("Access-Control-Allow-Origin: *");
//       // header("Access-Control-Allow-Origin: https://66.135.10.179/api/bulk/documents");
//       if (file_exists($zipFilePath)) {    // Send the ZIP file to the client for download
//           header('Content-Type: application/zip');
//           header('Content-Disposition: attachment; filename="bulk_download.zip"');
//           header('Content-Length: ' . filesize($zipFilePath));
//           readfile($zipFilePath);
//           // exit;
//           // Output the ZIP file  // Output the ZIP file
// //   if (readfile($zipFilePath) === false) {
// //     // Handle readfile error
// //     throw new CoreException(500, 'Failed to read ZIP file.');
// // }
// // Now, delete all files within the directory
// $directory = '/usr/ictcore/data/bulk_document/';
// $files = glob($directory . '*'); // Get a list of all files in the directory

// foreach ($files as $file) {
//     if (is_file($file)) {
//         if (unlink($file)) {
//             echo "File removed successfully: $file";
//         } else {
//             echo "Failed to remove file: $file (Error: " . error_get_last()['message'] . ")";
//         }
//     }
// }
//       }
//     }

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
