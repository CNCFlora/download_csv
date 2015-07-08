<?php

include 'config.php';
include 'http.php';
include 'save.php';

$src = $_GET["consulta"];

$doc = es_get(ELASTICSEARCH,".kibana", "search", $src);

// Create query from search properties
$query_base = $doc->kibanaSavedObjectMeta->searchSourceJSON;
$json_query = json_decode($query_base);
$query_must = array();
$query_must_not = array();

if (property_exists($json_query, 'query')){
    $query_string = $json_query->query;
}
else {
    $query_string = (object) [ 'match_all' => (object)[]];
}

foreach($json_query->filter as $filter)
    if (!is_null($filter)) {
        //Skip filter if it is disabled
        if (!$filter->meta->disabled) {
            if (property_exists($filter, 'query')){
                $query_temp = (object) ['query' => $filter->query];
            }
            if (property_exists($filter, 'exists')){
                $query_temp = (object) ['exists' => $filter->exists];
            }
            if ($filter->meta->negate){
                $query_must_not[] = $query_temp;
            }
            else {
                $query_must[] = $query_temp;
            }
        }
    }

//Separate variables to perform query
$index = $json_query->index;
$columns = $doc->columns;

//Change to * if all columns
if (current($columns) === '_source'){
    $columns = array('*');
}

// Construct bool query
$query = (object) ['query' => (object) [
                     'filtered' => (object)[
                        'query' => $query_string
                     ]
                   ],
                    'fields' =>  array('*')
                  ];
//Adding queries
foreach ($query_must as $filter){
    $query->query->filtered->filter->bool->must[] = $filter;
}
foreach ($query_must_not as $filter){
    $query->query->filtered->filter->bool->must_not[] = $filter;
}

$result = search_post(ELASTICSEARCH, $index, $query);
$csv_array = array();
$header = array();

foreach($result as $doc){
    //Create result array
    $row_array = array();
    if (current($columns) === '*') {
        $columns = array_keys(get_object_vars($doc));
        //$columns = array_diff($columns, array('_id'));
    }
    foreach ($columns as $field) {
        if (property_exists($doc, $field)){
            $row_array[] = implode(';', $doc->$field);
        }
        else {
            $row_array[] = '';
        }
    }
    $csv_array[] = $row_array;
}
//Add header as first row
array_unshift($csv_array, $columns);
convert_to_csv($csv_array, 'report.csv', ',');
