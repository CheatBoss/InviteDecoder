<?php

function base64url_encode($data)
{
	return rtrim(strtr(base64_encode($data) , '+/', '_-') , '=');
}

function base64url_decode($data)
{
	return base64_decode(str_pad(strtr($data, '_-', '+/') , strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function decode_invite_link($link)
{
	$data = bin2hex(base64url_decode($link));
	$link_data[0] = substr($data, 0, 16);
	$link_data[1] = substr($data, 16, strlen($data));
	$invite_hash = base64url_encode(hex2bin($link_data[1])) . "==";
	$invite_chat_id = $link_data[0];
	$b1 = $invite_chat_id[6] . $invite_chat_id[7];
	$b2 = $invite_chat_id[4] . $invite_chat_id[5];
	$b3 = $invite_chat_id[2] . $invite_chat_id[3];
	$b4 = $invite_chat_id[0] . $invite_chat_id[1];
	$b5 = $invite_chat_id[14] . $invite_chat_id[15];
	$b6 = $invite_chat_id[12] . $invite_chat_id[13];
	$b7 = $invite_chat_id[10] . $invite_chat_id[11];
	$b8 = $invite_chat_id[8] . $invite_chat_id[9];
	$invite_chat_id = unpack("J", hex2bin($b1 . $b2 . $b3 . $b4 . $b5 . $b6 . $b7 . $b8)) [1];
	return ["link" => $link, "invite_chat_id" => $invite_chat_id, "invite_hash" => $invite_hash, "hash" => $link_data[1]];
}
// example

var_dump(decode_invite_link("AJQ1d583WQ6yAItekJGsIcCC"));
//result:
/* array(4) {
  ["link"]=>
  string(24) "AJQ1d583WQ6yAItekJGsIcCC"
  ["invite_chat_id"]=>
  int(8589934592240727967)
  ["invite_hash"]=>
  string(16) "sgCLXpCRrCHAgg=="
  ["hash"]=>
  string(20) "b2008b5e9091ac21c082"
} */
