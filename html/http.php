<?php

function http_get($url) {
  return json_decode(file_get_contents($url));
}

function http_post($url,$doc) {
  $opts = ['http'=>['method'=>'POST','content'=>json_encode($doc),'header'=>'Content-type: application/json']];
  $r = file_get_contents($url, NULL, stream_context_create($opts));
  return json_decode($r);
}

function http_put($url,$doc) {
  $opts = ['http'=>['method'=>'PUT','content'=>json_encode($doc),'header'=>'Content-type: application/json']];
  $r = file_get_contents($url, NULL, stream_context_create($opts));
  return json_decode($r);
}

function http_delete($url) {
  $opts = ['http'=>['method'=>'DELETE']];
  $r = file_get_contents($url, NULL, stream_context_create($opts));
  return json_decode($r);
}


function search($es,$db,$idx,$q) {
  $q = str_replace("=",":",$q);
  $url = $es.'/'.$db.'/'.$idx.'/_search?q='.urlencode($q);
  $r = http_get($url);

  //Get the number of documents
  $doc_size = $r->hits->total;

  //Query again specifying the number of wanted docs
  $url = $es.'/'.$db.'/'.$idx.'/_search?size='.$doc_size.'&q='.urlencode($q);
  $r = http_get($url);

  $arr =array();
  foreach($r->hits->hits as $hit) {
      $doc = $hit->_source;
      $doc->_id = $hit->_id;
      //$doc->_rev = $doc->rev;
      //unset($doc->id);
      //unset($doc->rev);
      $arr[] = $doc;
  }

  return $arr;
}

function es_get($es, $db, $type, $idx){
    $url = $es.'/'.$db.'/'.$type.'/'.$idx;
    $r = http_get($url);

    $doc = $r->_source;
    //$doc->_id = $r->_id;
    return $doc;

}

function es_post($es, $db, $data){
    $url = $es.'/'.$db;
    $r = http_post($url, $data);

    return $r;
}

function search_post($es, $db, $data){
    $r = es_post($es, $db.'/_search', $data);
    //Get the number of documents
    $doc_size = $r->hits->total;
    //Query again specifying the number of wanted docs
    $r = es_post($es, $db.'/_search?size='.$doc_size, $data);

    $arr =array();

    foreach($r->hits->hits as $hit) {
        if (property_exists($hit, 'fields')){
            $doc = $hit->fields;
            //$doc->_id = $hit->_id;
            $arr[] = $doc;
        }
    }

    return $arr;
}
