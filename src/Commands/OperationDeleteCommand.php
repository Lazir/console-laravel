<?php

/*
 * This file is part of the lucid-console project.
 *
 * (c) Vinelab <dev@vinelab.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lucid\Console\Commands;

use Lucid\Console\Str;
use Lucid\Console\Finder;
use Lucid\Console\Command;
use Lucid\Console\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * @author Ali Issa <ali@vinelab.com>
 */
class OperationDeleteCommand extends SymfonyCommand
{
    use Finder;
    use Command;
    use Filesystem;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'delete:operation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an existing Operation in a service';

    /**
     * The type of class being deleted.
     *
     * @var string
     */
    protected $type = 'Operation';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function fire()
    {
        try {
            $service = Str::service($this->argument('service'));
            $title = $this->parseName($this->argument('operation'));

            if (!$this->exists($operation = $this->findOperationPath($service, $title))) {
                $this->error('Operation class '.$title.' cannot be found.');
            } else {
                $this->delete($operation);

                $this->info('Operation class <comment>'.$title.'</comment> deleted successfully.');
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['operation', InputArgument::REQUIRED, 'The operation\'s name.'],
            ['service', InputArgument::OPTIONAL, 'The service from which the operation should be deleted.'],
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../Generators/stubs/operation.stub';
    }

    /**
     * Parse the operation name.
     *  remove the Operation.php suffix if found
     *  we're adding it ourselves.
     *
     * @param string $name
     *
     * @return string
     */
    protected function parseName($name)
    {
        return Str::operation($name);
    }
}
