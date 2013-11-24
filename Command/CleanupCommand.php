<?php

namespace Httpi\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class CleanupCommand extends ContainerAwareCommand
{
	
    protected function configure()
    {
        $this->setName('httpi:utils:cleanup')
			->addArgument("bundles", InputArgument::IS_ARRAY, '@TODO', array(""))
            ->setDescription('Cleanup entities task');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$fs = new Filesystem();
		$bundles = $input->getArgument('bundles');
		$dirs = $this->buildDirectories($bundles);
		
		// cleanup entities
		$output->writeln("Cleaning up directories <info>" . implode(" ", $dirs) . "</info>");
		$fs->remove($dirs);
    }
	
	private function buildDirectories(array $bundles)
	{
		$dirs = array();

        if (!is_array($bundles) || empty($bundles)) {
            ////@TODO: get registered bundles
        } else {
            foreach ($bundles as $bundlePath) {
                $dirs[] = $bundlePath . "Entity/";
            }
        }

		return $dirs;
	}
}
