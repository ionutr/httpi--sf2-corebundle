<?php

namespace Httpi\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class FileCleanupCommand extends ContainerAwareCommand
{
	
    protected function configure()
    {
        $this->setName('httpi:files:cleanup')
            ->setDescription('Cleanup files task');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$fs = new Filesystem();
		$dirs = array(
            "src/Httpi/Bundle/CoreBundle/Resources/files/download/",
            "src/Httpi/Bundle/CoreBundle/Resources/files/upload/",
            "src/Httpi/Bundle/CoreBundle/Resources/files/tmp/"
        );

        $fs->remove($dirs);
		
		// cleanup file directories
		$output->writeln("Cleaning up directories <info>" . implode(" ", $dirs) . "</info>");
		$fs->remove($dirs);

        // restore file directories, fresh
        foreach ($dirs as $dir) {
            $fs->mkdir($dir);
        }
    }
}
