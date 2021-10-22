<?php

declare(strict_types=1);

namespace DoctrineModule\Controller;

use DoctrineModule\Component\Console\Input\RequestInput;
use Laminas\Mvc\Console\View\ViewModel;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;

use function class_exists;
use function is_numeric;
use function sprintf;

/**
 * Index controller
 *
 * @deprecated 4.2.0 Usage of laminas/laminas-mvc-console is deprecated, integration will be removed in 5.0.0.
 *                   Please use ./vendor/bin/doctrine-module instead.
 */
class CliController extends AbstractActionController
{
    /** @var Application */
    protected $cliApplication;

    /** @var OutputInterface */
    protected $output;

    public function __construct(Application $cliApplication)
    {
        $this->cliApplication = $cliApplication;
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * Index action - runs the console application
     *
     * @return mixed
     */
    public function cliAction()
    {
        if (! class_exists(AbstractActionController::class)) {
            throw new RuntimeException(sprintf(
                'Using %s requires the package laminas/laminas-mvc-console, which is currently not installed.',
                __METHOD__
            ));
        }

        $exitCode = $this->cliApplication->run(new RequestInput($this->getRequest()), $this->output);

        if (is_numeric($exitCode)) {
            $model = new ViewModel();
            $model->setErrorLevel($exitCode);

            return $model;
        }
    }
}
