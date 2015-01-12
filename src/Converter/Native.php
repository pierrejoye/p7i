<?php
namespace P7i\Converter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\InlineHTML;

class Native extends NodeVisitorAbstract {
	private $dest;
	private $filename;
	private $native_counter;

	function __construct($target, $filename) {
		$this->dest = $target;
		$this->filename = $filename;
		$this->native_counter = 0;
	}

	function leaveNode(Node $node) {
		if ($node instanceof InlineHTML) {
			echo $node->value;
			print_r($node);
			$c = $node->value;
			$c = str_replace('---- P7I','', $c);
			$node->value = '';
			file_put_contents($this->dest . DIRECTORY_SEPARATOR . $this->filename . '_' . $this->native_counter. '.c', $c);
			$this->native_counter++;
		}
	}
}


