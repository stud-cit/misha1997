<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ScopusController extends Controller {

  protected $scopus_api = 'https://api.elsevier.com/content/';
  protected $scopus_api_key = '01bdf01a22c7c48c8b10fd1dd890e76b';

  function getPublicationsAuthor($scopusId) {
    $data = json_decode(file_get_contents($this->scopus_api . 'search/scopus?query=au-id(' . $scopusId . ')&apiKey=' . $this->scopus_api_key), true);
    return response()->json($data);
  }
}
