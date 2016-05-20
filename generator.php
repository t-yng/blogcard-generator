<?php
require_once './vendor/autoload.php';

use Goutte\Client;

$url = rawurldecode($_GET['url']);
$host = parse_url($url)['host'];

$client = new Client();
$crawler = $client->request('GET', $url);

$og_title = getContent($crawler, 'og:title');
$og_image = getContent($crawler, 'og:image');
$og_description = getContent($crawler, 'og:description');

$html = "<div style='width:100%;max-width:500px;margin:0 0 20px 0;background:#fff;border:1px solid#ccc;border-radius:5px;box-sizing:border-box;padding:12px;'><div style='width:100px;height:100px;float:right;margin:0 0 10px 10px;padding:0;position:relative;overflow:hidden;'><a href='http://sakueji.com/blogcard-bookmarklet/'style='position:absolute;width:1000%;left:50%;margin:0 0 0 -500%;text-align:center;'><img src='{$og_image}'style='width:auto;height:100px;margin:0;vertical-align:middle;display:inline;'></a></div><p style='margin:0;'><a href='{$url}'style='color:#333;font-weight:bold;text-decoration:none;font-size:17px;margin:0 0 10px 0;line-height:1.5;'>{$og_title}</a></p><p style='margin:0;color:#666;font-size:11px;line-height:1.5;'>{$og_description}</p><div style='border-top:1px solid#eee;clear:both;margin:10px 0 0 0;padding:0;'><p style='color:#999;margin:3px 0 0 0;font-size:11px;'><img src='http://favicon.hatena.ne.jp/?url={$url}'style='margin:0 5px 0 0;padding:0;border:none;display:inline;vertical-align:middle;'>{$host}<img src='http://b.hatena.ne.jp/entry/image/{$url}'style='margin:0 0 0 5px;padding:0;border:none;display:inline;vertical-align:middle;'></p></div></div>";

$json_text = ['html' => $html];
$json = json_encode($json_text);

header('Access-Control-Allow-Origin: *');
if(isset($_GET['callback'])) {
	header('Content-Type: text/javascript; charset=utf-8');
	echo "{$_GET['callback']}({$json})";
}
else {
  header('Content-Type: application/json;');
  echo $json;
}

function getContent($crawler, $property) {
	return $crawler->filter("meta[property='{$property}']")->attr('content');
}
