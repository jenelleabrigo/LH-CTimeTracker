<?php
/**
 * URIルーティング
 * URL Routing
 *
 * 先に設定されたものほど優先度は高い
 * Higher routes will always take precedence over lower ones.
 */

// index.html対策
$route[':any/index.html'] = '$1/index';

// ニュース
// For News
$route['news/post_:num.html'] = 'news/detail/$1';

// お問い合わせ
// For contact
$route['contact/']                = 'contact/index';
$route['contact/index.html']      = 'contact/index';
$route['contact/confirm.html']    = 'contact/confirm';
$route['contact/error.html']      = 'contact/error';
$route['contact/result.html']     = 'contact/result';

/* End of file routes.php */