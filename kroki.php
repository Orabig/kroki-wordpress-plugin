<?php

/*
*  * Plugin Name: Kroki diagram insert
*/

function base64url_encode($data) {
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	  }

function encode($data) {
  return base64url_encode(gzcompress($data));
}

function kroki_convert( $atts, $content=null ) {
  $atts = shortcode_atts( array(
	'type' => 'graphviz',
	'format' => 'svg'
	), $atts, 'kroki' );
  $type=$atts['type'];
  $format=$atts['format'];
  $content=str_replace('<br>',"\n",$content);
  $content=str_replace('<p>',"\n",$content);
  $content=str_replace('&#8216;',"'",$content);
  $content=str_replace('&#8217;',"'",$content);
  $content=str_replace('&#8220;','"',$content);
  $content=str_replace('&#8221;','"',$content);
  $content=str_replace('&#8243;','"',$content);
  $content=str_replace('&#8211;','-',$content);
  $content=str_replace('&lt;','<',$content);
  $url = "http://127.0.0.1:31487/{$type}/{$format}/".encode($content);
  $response = wp_remote_get($url);
  if ( is_array( $response ) && ! is_wp_error( $response ) ) {
  	$headers = $response['headers']; // array of http header lines
	$body    = $response['body']; // use the content
	if ($body=='' || str_starts_with('Error', $body)) {
	return 'Empty kroki response for type={$type} on: <pre>'.$content.'</pre><hr>';
	} else {
	return $body;
	}
  }
  return "[Invalid {$type}]<h3>content:</h3><pre>{$content}</pre><hr/>";
}
add_shortcode( 'kroki', 'kroki_convert' );

