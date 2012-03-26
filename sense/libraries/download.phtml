<?php 

// define several constants 
download::secureDefine("DEF_MIMETYPE", "application/octet-stream"); 
download::secureDefine("GZ_MIMETYPE", "application/octet-stream"); 
download::secureDefine("DEF_READ_BUFFER_SIZE", 32768); // 2^15 
// if file size larger than threshold, then compress file 
download::secureDefine("DEF_COMPRESS_THRESHOLD", 102400); // 100 kB 

class download { 

  // This defines a constant. If the constant is already defined, it 
  // triggers an error. 
  // $sDefName: the name of the constant 
  // $vDefValue: the value of the constant 
  // Returns nothing 

  function secureDefine($sDefName, $vDefValue) 
  { 
      if (defined($sDefName)) { 
          trigger_error("$sDefName already defined in __FILE__ line __LINE__", 
                  E_USER_WARNING); 
      } else { 
          define($sDefName, $vDefValue); 
      } /* endif */ 
  } /* end function */ 

  // This function calls the function trigger_error with a pre-defined format. 
  // $sMessage: the error message 
  // $iErrorClass: the error class (see php function error_reporting()) 
  // Returns nothing. 

  function error($sMessage, $iErrorClass) 
  { 
      trigger_error("$sMessage in " . __FILE__ . " line " . __LINE__, $iErrorClass); 
  } /* end function */ 

  // This function compresses an existing file into a temporary file. 
  // The function terminates on error using the classes' error function. 
  // $sFileName: the name of the existing file 
  // $iReadBufferSize: the buffer size which is used by the fread() function 
  // Returns the name of the compressed file. 

  function compress($sFileName, 
                  $iReadBufferSize = DEF_READ_BUFFER_SIZE) 
  { 
      $hFPi = NULL;   // input file handle 
      $hFPo = NULL;   // output file handle 
      $sTmpFile = ""; // name of the temporary file 
      $sBuf = "";     // read buffer 

      // open the input file 
      $hFPi = fopen ("$sFileName", "rb"); 
      if ($hFPi == FALSE) { 
          download::error("Can't open file $sFileName", E_USER_ERROR); 
      } /* endif */ 

      // create a temporary file name. Let the function use the system constants. 
      $sTmpFile = tempnam(NULL, NULL); 
      if ($sTmpFile == FALSE) { 
          download::error("Can't create temporary file name", E_USER_ERROR); 
      } /* endif */ 

      // open new file for compression 
      $hFPo = gzopen ($sTmpFile, "wb"); 
      if ($hFPo == FALSE) { 
          download::error("Can't open temporary file $sTmpName", E_USER_ERROR); 
      } /* endif */ 

      // read from input and write into output file. 
      while (!feof($hFPi)) { 
          $sBuf = fread($hFPi, $iReadBufferSize); 
          gzwrite ($hFPo, $sBuf); 
      } /* endwhile */ 

      // close both open files. 
      gzclose($hFPo); 
      fclose($hFPi); 

      return $sTmpFile; 

  } /* end function */ 

  // This is the main function of the class. It determines the file size and 
  // compresses it if file size is larger than the given limit. Then the 
  // headers are sent and finally the download data. 
  // Important! No headers must be sent before calling this function!!! 
  // $sFileName: the name of the file which will be sent 
  // $sDownloadName: the name as it will be shown in the browser 
  // $sMimeType: the file (mime) type 
  // $iCompressThreshold: the limit for compressing files. 
  //       Set to --1 if no compression shall be performed. 
  // $iReadBufferSize: the buffer size used by the fread() function 
  // Returns nothing. 

  function dlFile($sFileName, 
                  $sDownloadName = NULL, 
                  $sMimeType = NULL, 
                  $iCompressThreshold = NULL, 
                  $iReadBufferSize = NULL) 
  { 

      $hFPi = NULL; // input file handle 
      $hFPo = NULL; // output file handle 
      $sBuf = "";   // read buffer 
      $bRemoveFile = FALSE; // boolean flag used for temporary compressed files 

      // some defaults 
      if ($sMimeType == NULL)          $sMimeType = DEF_MIMETYPE; 
      if ($iCompressThreshold == NULL) $iCompressThreshold = DEF_COMPRESS_THRESHOLD; 
      if ($iReadBufferSize == NULL)    $iReadBufferSize = DEF_READ_BUFFER_SIZE; 

      // buffer output in order to allow modification of http header 
      ob_start(); 

      // if no download name is given, use file name as default 
      if ($sDownloadName == NULL) { 
         $sDownloadName = $sFileName; 
      } /* endif */ 

      // file size > compression threshold? 
      if ($iCompressThreshold > 0 && filesize($sFileName) >= $iCompressThreshold) { 

          // compress into temporary file, store resulting file name 
          $sFileName = download::compress($sFileName, $iCompressThreshold); 
          // mark file for later removal 
          $bRemoveFile = TRUE; 
          // sent first header line 
          header("Content-Type: " . GZ_MIMETYPE); 
          // add extension to download file name 
          $sDownloadName .= ".gz"; 

      } else { 

          // sent first header line 
          header("Content-Type: $sMimeType"); 
      } /* endif */ 

      // sent second header line 
      header("Content-Disposition: inline; filename=$sDownloadName"); 

      // open input file (may have been compressed) 
      $hFPi = fopen ("$sFileName", "rb"); 
      if ($hFPi == FALSE) { 
          download::error("Can't open file $sFileName", E_USER_ERROR); 
          } /* endif */ 

      // sent third header line with file size 
      header("Content-Length: " . filesize($sFileName)); 

      // now sent data 
      while (!feof($hFPi)) { 
          $sBuf = fread ($hFPi, $iReadBufferSize); 
          echo $sBuf; 
      } /* endwhile */ 

      // close input file 
      fclose ($hFPi); 

      // flush data to client 
      ob_end_flush(); 

      // now remove any temporary file 
      if ($bRemoveFile == TRUE) { 
          if (unlink ($sFileName) == FALSE) { 
             download::error("Can't unlink file $sFileName", E_USER_ERROR); 
          } /* endif */ 
      } /* endif */ 

  } /* end function */ 

} 
