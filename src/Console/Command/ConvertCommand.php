<?php
namespace P7i\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpParser\Parser;
use PhpParser\Lexer;
use PhpParser\NodeDumper;
use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

use P7i\Converter\Native;

class ConvertCommand extends Command {
	protected function configure()
	{
		$this
			->setName('convert')
			->setDescription('Convert input directory to final extension source tree')
			->addArgument(
			'path',
			InputArgument::OPTIONAL,
			'Path to the project root directory (default pwd)',
			getcwd()
			)
			->addArgument(
			'dest',
			InputArgument::OPTIONAL,
			'Path to the project root directory (default ./extsrc)',
			getcwd() . DIRECTORY_SEPARATOR . 'extsrc'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$path = rtrim($input->getArgument('path'), '/\\');
		$dest = rtrim($input->getArgument('dest'), '/\\');
		$parser = new Parser(new Lexer);
		$script = file_get_contents($path);
		$nodeDumper = new NodeDumper;

		$traverser     = new NodeTraverser;
		$prettyPrinter = new PrettyPrinter\Standard;

		$traverser->addVisitor(new Native($dest, basename($path)));
		try {
			$stmts = $parser->parse($script);
			$traverser->traverse($stmts);
//			echo $nodeDumper->dump($stmts), "\n";
		} catch (PhpParser\Error $e) {
			echo 'Parse Error: ', $e->getMessage();
		}
	}
}
