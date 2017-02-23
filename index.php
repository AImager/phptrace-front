<?php


$file_name = "phptrace.log";

// $file = fopen("test.log", "a+");
//
// $content = stream_get_contents($file);
//
//


class node {
	public $text;
	public $nodes;
	public $result;

	public function __construct($text) {
		$this->text = $text;
		$this->nodes = [];
		$this->result = "";
	}

	public function add_node(node $node) {
		array_push($this->nodes, $node);
	}
}


function insertNode($last_level, $curr_level, &$stack, $node, $text) {
	$new_node = new node($text);
	if($last_level == $curr_level) {
		$node->add_node($new_node);
	} else if ($last_level < $curr_level) {
		array_push($stack, $node);
		$node = end($node->nodes);
		$node->add_node($new_node);
	}
	return $node;
}


function change_result($last_level, $curr_level, &$stack, $node, $result) {
	if($last_level == $curr_level) {
		end($node->nodes)->result = $result;
	} else if ($last_level > $curr_level) {
		$node->result = $result;
		$node = array_pop($stack);
	}
	return $node;
}

// $str = "111211";
// $stack = [];
// $node = $header = new node("text");
//
// for($i = 0; $i < strlen($str)-1; $i++) {
// 	$last_level = $str[$i];
// 	$curr_level = $str[$i+1];
// 	// echo "$last_level \n";
// 	//
// 	// echo "$curr_level \n";
// 	$node = insertNode($last_level, $curr_level, $stack, $node);
// }
// // print_r(json_decode(json_encode($header), true));




$node = $header = new node("header");
$stack = [];

$file = file($file_name);

$last_level = 0;

for($i = 1;$i < count($file) - 1;$i++) {
	$match = [];
	// $curr_level = $file[$i];
	if(preg_match('/^[^]]*]([\s]*)> ([\s\S]*)$/', $file[$i], $match)) {
		// preg_match('/^[^]]*]([\s]*)>/', $file[$i], $match);
		// if(count($match) < 1)  print_r($match);
		$space = $match[1];
		$all_str = $match[2];

		echo "$space \n";

		preg_match('/^[^]]*]([\s]*)> ([\s\S]*?) called at ([\s\S]*)/', $file[$i], $match);

		// print_r($match);exit;
		//

		$call_api = isset($match[2])?$match[2]:$all_str;
		// $call_at = $match[3];
		// str_replace("\t", " ", $match[1]);
		$curr_level = strlen($space)/4;
		// print_r($curr_level . "\n");
		$node = insertNode($last_level, $curr_level, $stack, $node, trim($call_api));
	} else {
		preg_match('/^[^]]*]([\s]*)< ([\s\S]*)$/', $file[$i], $match);
		$space = $match[1];
		$all_str = $match[2];

		$match = [];
		preg_match('/^[^]]*]([\s]*)< [^=]*? = ([\s\S]*?) called at ([\s\S]*)/', $file[$i], $match);
		// str_replace("\t", " ", $match[1]);
		// print_r($match);exit;

		$result = isset($match[2])?$match[2]:$all_str;
		// $call_at = $match[3];
		$curr_level = strlen($space)/4;
		$node = change_result($last_level, $curr_level, $stack, $node, trim($result));
	}

	$last_level = $curr_level;

	// print_r(json_decode(json_encode($header), true));sleep(5);
}

$data = json_decode(json_encode($header), true);

// print_r($data);exit;


function digui(&$array) {
	if(count($array['nodes']) == 0) {
		unset($array['nodes']);
	} else {
		foreach($array['nodes'] as $key => $val) {
			digui($array['nodes'][$key]);
		}
	}
}

digui($data);

$data = json_encode($data['nodes']);




// print_r($data);exit;


include "template.php";
