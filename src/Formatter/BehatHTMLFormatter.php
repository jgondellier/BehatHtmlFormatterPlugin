<?php

namespace gondellier\BehatHTMLFormatter\Formatter;

use Behat\Behat\EventDispatcher\Event\AfterFeatureTested;
use Behat\Behat\EventDispatcher\Event\AfterOutlineTested;
use Behat\Behat\EventDispatcher\Event\AfterScenarioTested;
use Behat\Behat\EventDispatcher\Event\AfterStepTested;
use Behat\Behat\EventDispatcher\Event\BeforeFeatureTested;
use Behat\Behat\EventDispatcher\Event\BeforeOutlineTested;
use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Tester\Result\ExecutedStepResult;
use Behat\Behat\Tester\Result\SkippedStepResult;
use Behat\Behat\Tester\Result\StepResult;
use Behat\Behat\Tester\Result\UndefinedStepResult;
use Behat\Testwork\Counter\Memory;
use Behat\Testwork\Counter\Timer;
use Behat\Testwork\EventDispatcher\Event\AfterExerciseCompleted;
use Behat\Testwork\EventDispatcher\Event\AfterSuiteTested;
use Behat\Testwork\EventDispatcher\Event\BeforeExerciseCompleted;
use Behat\Testwork\EventDispatcher\Event\BeforeSuiteTested;
use Behat\Testwork\Output\Formatter;
use Behat\Testwork\Output\Printer\OutputPrinter;
use gondellier\BehatHTMLFormatter\Classes\Feature;
use gondellier\BehatHTMLFormatter\Classes\Scenario;
use gondellier\BehatHTMLFormatter\Classes\Step;
use gondellier\BehatHTMLFormatter\Classes\Suite;
use gondellier\BehatHTMLFormatter\Printer\FileOutputPrinter;
use gondellier\BehatHTMLFormatter\Renderer\BaseRenderer;

/**
 * Class BehatHTMLFormatter.
 */
class BehatHTMLFormatter implements Formatter
{
    //<editor-fold desc="Variables">
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $timer;

    /**
     * @var
     */
    private $memory;

    /**
     * @param string $outputPath where to save the generated report file
     */
    private $outputPath;

    /**
     * @param string $base_path Behat base path
     */
    private $base_path;

    /**
     * Printer used by this Formatter.
     *
     * @param $printer OutputPrinter
     */
    private $printer;

    /**
     * Renderer used by this Formatter.
     *
     * @param $renderer BaseRenderer
     */
    private $renderer;

    /**
     * Flag used by this Formatter.
     *
     * @param $print_args boolean
     */
    private $print_args;

    /**
     * Flag used by this Formatter.
     *
     * @param $print_outp boolean
     */
    private $print_outp;

    /**
     * Flag used by this Formatter.
     *
     * @param $loop_break boolean
     */
    private $loop_break;

    /**
     * @var array
     */
    private $suites;

    /**
     * @var Suite
     */
    private $currentSuite;

    /**
     * @var int
     */
    private $featureCounter = 1;

    /**
     * @var Feature
     */
    private $currentFeature;

    /**
     * @var Scenario
     */
    private $currentScenario;

    /**
     * @var Scenario[]
     */
    private $failedScenarios = array();

    /**
     * @var Scenario[]
     */
    private $pendingScenarios = array();

    /**
     * @var Scenario[]
     */
    private $passedScenarios = array();

    /**
     * @var Feature[]
     */
    private $failedFeatures = array();

    /**
     * @var Feature[]
     */
    private $passedFeatures = array();

    /**
     * @var Step[]
     */
    private $failedSteps = array();

    /**
     * @var Step[]
     */
    private $passedSteps = array();

    /**
     * @var Step[]
     */
    private $pendingSteps = array();

    /**
     * @var Step[]
     */
    private $skippedSteps = array();

    //</editor-fold>

    //<editor-fold desc="Formatter functions">

    /**
     * BehatHTMLFormatter constructor.
     * @param $name
     * @param $renderer
     * @param $filename
     * @param $print_args
     * @param $print_outp
     * @param $loop_break
     * @param $base_path
     */
    public function __construct($name, $renderer, $filename, $print_args, $print_outp, $loop_break, $base_path)
    {
        $this->name = $name;
        $this->base_path = $base_path;
        $this->print_args = $print_args;
        $this->print_outp = $print_outp;
        $this->loop_break = $loop_break;
        $this->renderer = new BaseRenderer($renderer, $base_path);
        $this->printer = new FileOutputPrinter($this->renderer->getNameList(), $filename, $base_path);
        $this->timer = new Timer();
        $this->memory = new Memory();
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents():array
    {
        return array(
            'tester.exercise_completed.before' => 'onBeforeExercise',
            'tester.exercise_completed.after' => 'onAfterExercise',
            'tester.suite_tested.before' => 'onBeforeSuiteTested',
            'tester.suite_tested.after' => 'onAfterSuiteTested',
            'tester.feature_tested.before' => 'onBeforeFeatureTested',
            'tester.feature_tested.after' => 'onAfterFeatureTested',
            'tester.scenario_tested.before' => 'onBeforeScenarioTested',
            'tester.scenario_tested.after' => 'onAfterScenarioTested',
            'tester.outline_tested.before' => 'onBeforeOutlineTested',
            'tester.outline_tested.after' => 'onAfterOutlineTested',
            'tester.step_tested.after' => 'onAfterStepTested',
        );
    }

    /**
     * Returns formatter name.
     *
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBasePath():string
    {
        return $this->base_path;
    }

    /**
     * Returns formatter description.
     *
     * @return string
     */
    public function getDescription():string
    {
        return 'Formatter for teamcity';
    }

    /**
     * Returns formatter output printer.
     *
     * @return OutputPrinter
     */
    public function getOutputPrinter():OutputPrinter
    {
        return $this->printer;
    }

    /**
     * Sets formatter parameter.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setParameter($name, $value):void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Returns parameter name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->parameters[$name];
    }

    /**
     * Returns output path.
     *
     * @return string output path
     */
    public function getOutputPath():string
    {
        return $this->printer->getOutputPath();
    }

    /**
     * Returns if it should print the step arguments.
     *
     * @return bool
     */
    public function getPrintArguments():bool
    {
        return $this->print_args;
    }

    /**
     * Returns if it should print the step outputs.
     *
     * @return bool
     */
    public function getPrintOutputs():bool
    {
        return $this->print_outp;
    }

    /**
     * Returns if it should print scenario loop break.
     *
     * @return bool
     */
    public function getPrintLoopBreak():bool
    {
        return $this->loop_break;
    }

    /**
     * @return Timer
     */
    public function getTimer():Timer
    {
        return $this->timer;
    }

    /**
     * @return Memory
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @return array
     */
    public function getSuites()
    {
        return $this->suites;
    }

    /**
     * @return Suite
     */
    public function getCurrentSuite()
    {
        return $this->currentSuite;
    }

    /**
     * @return int
     */
    public function getFeatureCounter()
    {
        return $this->featureCounter;
    }

    /**
     * @return Feature
     */
    public function getCurrentFeature()
    {
        return $this->currentFeature;
    }

    /**
     * @return Scenario
     */
    public function getCurrentScenario()
    {
        return $this->currentScenario;
    }

    /**
     * @return Scenario[]
     */
    public function getFailedScenarios()
    {
        return $this->failedScenarios;
    }

    /**
     * @return Scenario[]
     */
    public function getPendingScenarios()
    {
        return $this->pendingScenarios;
    }

    /**
     * @return Scenario[]
     */
    public function getPassedScenarios()
    {
        return $this->passedScenarios;
    }

    /**
     * @return Feature[]
     */
    public function getFailedFeatures()
    {
        return $this->failedFeatures;
    }

    /**
     * @return Feature[]
     */
    public function getPassedFeatures()
    {
        return $this->passedFeatures;
    }

    /**
     * @return Step[]
     */
    public function getFailedSteps()
    {
        return $this->failedSteps;
    }

    /**
     * @return Step[]
     */
    public function getPassedSteps()
    {
        return $this->passedSteps;
    }

    /**
     * @return Step[]
     */
    public function getPendingSteps()
    {
        return $this->pendingSteps;
    }

    /**
     * @return Step[]
     */
    public function getSkippedSteps()
    {
        return $this->skippedSteps;
    }

    //</editor-fold>

    //<editor-fold desc="Event functions">

    /**
     * @param BeforeExerciseCompleted $event
     */
    public function onBeforeExercise(BeforeExerciseCompleted $event)
    {
        $this->timer->start();

        $print = $this->renderer->renderBeforeExercise($this);
        $this->printer->write($print);
    }

    /**
     * @param AfterExerciseCompleted $event
     */
    public function onAfterExercise(AfterExerciseCompleted $event)
    {
        $this->timer->stop();

        $print = $this->renderer->renderAfterExercise($this);
        $this->printer->writeln($print);
    }

    /**
     * @param BeforeSuiteTested $event
     */
    public function onBeforeSuiteTested(BeforeSuiteTested $event)
    {
        $this->currentSuite = new Suite();
        $this->currentSuite->setName($event->getSuite()->getName());

        $print = $this->renderer->renderBeforeSuite($this);
        $this->printer->writeln($print);
    }

    /**
     * @param AfterSuiteTested $event
     */
    public function onAfterSuiteTested(AfterSuiteTested $event)
    {
        $this->suites[] = $this->currentSuite;

        $print = $this->renderer->renderAfterSuite($this);
        $this->printer->writeln($print);
    }

    /**
     * @param BeforeFeatureTested $event
     */
    public function onBeforeFeatureTested(BeforeFeatureTested $event)
    {
        $feature = new Feature();
        $feature->setId($this->featureCounter);
        ++$this->featureCounter;
        $feature->setName($event->getFeature()->getTitle());
        $feature->setDescription($event->getFeature()->getDescription());
        $feature->setTags($event->getFeature()->getTags());
        $feature->setFile($event->getFeature()->getFile());
        $feature->setScreenshotFolder($event->getFeature()->getTitle());
        $this->currentFeature = $feature;

        $print = $this->renderer->renderBeforeFeature($this);
        $this->printer->writeln($print);
    }

    /**
     * @param AfterFeatureTested $event
     */
    public function onAfterFeatureTested(AfterFeatureTested $event)
    {
        $this->currentSuite->addFeature($this->currentFeature);
        if ($this->currentFeature->allPassed()) {
            $this->passedFeatures[] = $this->currentFeature;
        } else {
            $this->failedFeatures[] = $this->currentFeature;
        }

        $print = $this->renderer->renderAfterFeature($this);
        $this->printer->writeln($print);
    }

    /**
     * @param BeforeScenarioTested $event
     */
    public function onBeforeScenarioTested(BeforeScenarioTested $event)
    {
        $scenario = new Scenario();
        $scenario->setName($event->getScenario()->getTitle());
        $scenario->setTags($event->getScenario()->getTags());
        $scenario->setLine($event->getScenario()->getLine());
        $scenario->setScreenshotName($event->getScenario()->getTitle());
        $scenario->setScreenshotPath(
            $this->printer->getOutputPath().
            '/assets/screenshots/'.
            preg_replace('/\W/', '', $event->getFeature()->getTitle()).'/'.
            preg_replace('/\W/', '', $event->getScenario()->getTitle()).'.png'
        );
        $this->currentScenario = $scenario;

        $print = $this->renderer->renderBeforeScenario($this);
        $this->printer->writeln($print);
    }

    /**
     * @param AfterScenarioTested $event
     */
    public function onAfterScenarioTested(AfterScenarioTested $event)
    {
        $scenarioPassed = $event->getTestResult()->isPassed();

        if ($scenarioPassed) {
            $this->passedScenarios[] = $this->currentScenario;
            $this->currentFeature->addPassedScenario();
            $this->currentScenario->setPassed(true);
        } elseif (StepResult::PENDING == $event->getTestResult()->getResultCode()) {
            $this->pendingScenarios[] = $this->currentScenario;
            $this->currentFeature->addPendingScenario();
            $this->currentScenario->setPending(true);
        } else {
            $this->failedScenarios[] = $this->currentScenario;
            $this->currentFeature->addFailedScenario();
            $this->currentScenario->setPassed(false);
            $this->currentScenario->setPending(false);
        }

        $this->currentScenario->setLoopCount(1);
        $this->currentFeature->addScenario($this->currentScenario);

        $print = $this->renderer->renderAfterScenario($this);
        $this->printer->writeln($print);
    }

    /**
     * @param BeforeOutlineTested $event
     */
    public function onBeforeOutlineTested(BeforeOutlineTested $event)
    {
        $scenario = new Scenario();
        $scenario->setName($event->getOutline()->getTitle());
        $scenario->setTags($event->getOutline()->getTags());
        $scenario->setLine($event->getOutline()->getLine());
        $this->currentScenario = $scenario;

        $print = $this->renderer->renderBeforeOutline($this);
        $this->printer->writeln($print);
    }

    /**
     * @param AfterOutlineTested $event
     */
    public function onAfterOutlineTested(AfterOutlineTested $event)
    {
        $scenarioPassed = $event->getTestResult()->isPassed();

        if ($scenarioPassed) {
            $this->passedScenarios[] = $this->currentScenario;
            $this->currentFeature->addPassedScenario();
            $this->currentScenario->setPassed(true);
        } elseif (StepResult::PENDING == $event->getTestResult()->getResultCode()) {
            $this->pendingScenarios[] = $this->currentScenario;
            $this->currentFeature->addPendingScenario();
            $this->currentScenario->setPending(true);
        } else {
            $this->failedScenarios[] = $this->currentScenario;
            $this->currentFeature->addFailedScenario();
            $this->currentScenario->setPassed(false);
            $this->currentScenario->setPending(false);
        }

        $this->currentScenario->setLoopCount(sizeof($event->getTestResult()));
        $this->currentFeature->addScenario($this->currentScenario);

        $print = $this->renderer->renderAfterOutline($this);
        $this->printer->writeln($print);
    }

    /**
     * @param BeforeStepTested $event
     */
    public function onBeforeStepTested(BeforeStepTested $event)
    {
        $print = $this->renderer->renderBeforeStep($this);
        $this->printer->writeln($print);
    }

    /**
     * @param AfterStepTested $event
     */
    public function onAfterStepTested(AfterStepTested $event)
    {
        $result = $event->getTestResult();

        /** @var Step $step */
        $step = new Step();
        $step->setKeyword($event->getStep()->getKeyword());
        $step->setText($event->getStep()->getText());
        $step->setLine($event->getStep()->getLine());
        $step->setResult($result);
        $step->setResultCode($result->getResultCode());

        if ($event->getStep()->hasArguments()) {
            $object = $this->getObject($event->getStep()->getArguments());
            $step->setArgumentType($object->getNodeType());
            $step->setArguments($object);
        }

        //What is the result of this step ?
        if ($result instanceof UndefinedStepResult) {
            //pending step -> no definition to load
            $this->pendingSteps[] = $step;
        } else if ($result instanceof SkippedStepResult) {
            //skipped step
            /* @var ExecutedStepResult $result */
            $step->setDefinition($result->getStepDefinition());
            $this->skippedSteps[] = $step;
        } else if ($result instanceof ExecutedStepResult) {
            $step->setDefinition($result->getStepDefinition());
            $exception = $result->getException();
            if ($exception) {
                if ($exception instanceof PendingException) {
                    $step->setException($exception->getMessage());
                    $this->pendingSteps[] = $step;
                } else {
                    $step->setException($exception->getMessage());
                    $this->failedSteps[] = $step;
                }
            } else {
                $step->setOutput($result->getCallResult()->getStdOut());
                $this->passedSteps[] = $step;
            }
        }

        $this->currentScenario->addStep($step);

        $print = $this->renderer->renderAfterStep($this);
        $this->printer->writeln($print);
    }

    //</editor-fold>

    /**
     * @param array $arguments
     * @return mixed
     */
    public function getObject($arguments)
    {
        foreach ($arguments as $argument => $args) {
            return $args;
        }
    }
}
