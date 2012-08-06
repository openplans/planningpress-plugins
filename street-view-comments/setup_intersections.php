<?php
$data = array(
  array(
    "lat"=> 40.71761927918099,
    "lng"=> -73.9886873960495,
    "name"=> "Essex St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.71786323114908,
    "lng"=> -73.98949205875397,
    "name"=> "Ludlow St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.71809498469073,
    "lng"=> -73.99024307727814,
    "name"=> "Orchard St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.71822915742504,
    "lng"=> -73.99071514606476,
    "name"=> "Allen St. Northbound",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.71832673742558,
    "lng"=> -73.99099409580231,
    "name"=> "Allen St. Southbound",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.71855035772068,
    "lng"=> -73.99177730083466,
    "name"=> "Eldridge St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.718802437879894,
    "lng"=> -73.9926141500473,
    "name"=> "Forsyth St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.719050451298514,
    "lng"=> -73.99338126182556,
    "name"=> "Chrystie St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.71937571339776,
    "lng"=> -73.99442195892334,
    "name"=> "Bowery",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.71983920914318,
    "lng"=> -73.99529099464417,
    "name"=> "Elizabeth St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72015227023285,
    "lng"=> -73.9960527420044,
    "name"=> "Mott St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72048159264724,
    "lng"=> -73.9968466758728,
    "name"=> "Mulberry St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72068081158845,
    "lng"=> -73.99738311767578,
    "name"=> "Baxter St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.720782453675696,
    "lng"=> -73.99766743183136,
    "name"=> "Cleveland Pl.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72105078804027,
    "lng"=> -73.99823606014252,
    "name"=> "Lafayette St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72138823729656,
    "lng"=> -73.99906754493713,
    "name"=> "Crosby St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72174194733119,
    "lng"=> -73.99988293647766,
    "name"=> "Broadway",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.7221525738643,
    "lng"=> -74.00069296360016,
    "name"=> "Mercer St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.722530673283316,
    "lng"=> -74.0014386177063,
    "name"=> "Greene St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72290877055447,
    "lng"=> -74.00220036506653,
    "name"=> "Wooster St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.723307193311804,
    "lng"=> -74.00297820568085,
    "name"=> "W Broadway",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72367308966147,
    "lng"=> -74.00367558002472,
    "name"=> "Thompson St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.724043049481025,
    "lng"=> -74.00438368320465,
    "name"=> "Sullivan St.",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72415688285792,
    "lng"=> -74.00458753108978,
    "name"=> "6th Ave. Northbound",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.7242869779074,
    "lng"=> -74.00481283664703,
    "name"=> "6th Ave. Southbound",
    "show"=> true,
    "label"=> false
  ),
  array(
    "lat"=> 40.72438861448786,
    "lng"=> -74.00600373744965,
    "name"=> "Varick St.",
    "show"=> true,
    "label"=> false
  )
);

$data = array_reverse($data);
$i = 0;
foreach($data as $in) {
  $i++;
  $post = array(
    'post_title' => $in['name'],
    'post_type' => 'svc_intersection',
    'post_status' => 'publish',
    'menu_order' => $i
  );

  $post_id = wp_insert_post($post);

  add_post_meta($post_id, 'lat', $in['lat']);
  add_post_meta($post_id, 'lng', $in['lng']);
  add_post_meta($post_id, 'show', $in['show']);
  add_post_meta($post_id, 'label', $in['label']);
}


?>