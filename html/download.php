<?php

include 'config.php';
include 'http.php';
include 'save.php';

$src = $_POST["src"];

$doc = es_get(ELASTICSEARCH,".kibana", "search", $src);

// Create query from search properties
$query_base = $doc->kibanaSavedObjectMeta->searchSourceJSON;
$json_query = json_decode($query_base);
$query_must = array();
$query_must_not = array();
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

// Construct bool query
$query = (object) ['query' => (object) [
                        'filtered' => (object) [
                            'query' => (object) [
                                'match_all' => (object)[]
                            ],
                        ]
                    ],
                    'fields' => $columns
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

foreach($result as $doc){
    //Create result array
    $row_array = array();
    foreach ($columns as $field) {
        //TODO: iterate over all columns
        //if ($field === '_source') {
        //}
        if (property_exists($doc, $field)){
            $row_array[] = current($doc->$field);
        }
        else {
            $row_array[] = '';
        }
    }
    $csv_array[] = $row_array;
}
convert_to_csv($csv_array, 'report.csv', ',');
