<?php

namespace gondellier\BehatHTMLFormatter\Renderer;

use gondellier\BehatHTMLFormatter\Classes\Feature;
use gondellier\BehatHTMLFormatter\Classes\Scenario;
use gondellier\BehatHTMLFormatter\Classes\Step;
use gondellier\BehatHTMLFormatter\Classes\Suite;
use gondellier\BehatHTMLFormatter\Formatter\BehatHTMLFormatter;

/**
 * JSON renderer for Behat report.
 *
 * Class JsonRenderer
 */
class JsonRenderer
{
    /**
     * Renders before an exercise.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderBeforeExercise(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders after an exercise.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderAfterExercise(BehatHTMLFormatter $obj): string
    {
        $print =array();
        $print['summary'] = [
            'date' => date('d/m/Y H:i:s'),
            'timer' => (string) $obj->getTimer(),
            'outputPath' => $obj->getOutputPath(),
            'features'=>[
                'failed'=>count($obj->getFailedFeatures()),
                'passed'=>count($obj->getPassedFeatures())
            ],
            'scenarios'=>[
                'failed'=>count($obj->getFailedScenarios()),
                'pending'=>count($obj->getPendingScenarios()),
                'passed'=>count($obj->getPassedScenarios())
            ],
            'steps'=>[
                'failed'=>count($obj->getFailedSteps()),
                'skipped'=>count($obj->getSkippedSteps()),
                'passed'=>count($obj->getPassedSteps())
            ]
        ];
        foreach($obj->getSuites() as $suites){
            $nbScenarioSuccess = 0;
            $nbScenarioPending = 0;
            $nbScenarioFailed = 0;
            /** @var $suites Suite */
            if($suites->getFeatures()){
                $printSuites= [
                    'name' =>$suites->getName()
                ];
                foreach ($suites->getFeatures() as $feature){
                    /** @var $feature Feature */
                    $printFeature = [
                        'behatId' => $feature->getId(),
                        'name' => $feature->getName(),
                        'description' => $feature->getDescription(),
                        'passedClass' => $feature->getPassedClass(),
                    ];
                    if($feature->getPassedClass()){
                        $printFeature['isPassed'] = True;
                    }else{
                        $printFeature['isFailed'] = False;
                    }

                    if($feature->getTags()){
                        foreach($feature->getTags() as $tag){
                            $printFeature['tags'][] = $tag;
                        }
                    }
                    if($feature->getScenarios()){
                        foreach($feature->getScenarios() as $scenario){
                            /** @var $scenario Scenario */
                            $printScenario = [
                                'behatId' => $scenario->getId(),
                                'name' => $scenario->getName(),
                                'isPassed' => $scenario->isPassed(),
                                'isPending' => $scenario->isPending(),
                                'tags' => $scenario->getTags(),
                            ];
                            if($scenario->isPassed()) {
                                $nbScenarioSuccess++;
                            }elseif($scenario->isPending()){
                                $nbScenarioPending++;
                            }else{
                                $nbScenarioFailed++;
                            }
                            if($scenario->getSteps()){
                                foreach($scenario->getSteps() as $step){
                                    /** @var $step Step */
                                    $printStep = [
                                        'isPassed' => $step->isPassed(),
                                        'isPending' => $step->isPending(),
                                        'isSkipped' => $step->isSkipped(),
                                        'isFailed' => $step->isFailed(),
                                        'keyword' => $step->getKeyword(),
                                        'text' => $step->getText(),
                                        'arguments' => $step->getArguments(),
                                        'exceptions' => $step->getException(),
                                        'output' => $step->getOutput(),
                                        'line' => $step->getLine(),
                                        'result' => $step->getResult(),
                                        'resultCode' => $step->getResultCode(),
                                        'argumentType' => $step->getArgumentType(),
                                        'definition' => $step->getDefinition(),
                                    ];
                                    if ($step->getException()){
                                        $featureFolder = preg_replace('/\W/', '_', $feature->getName());
                                        //Screenshot
                                        $screenshotFileName = preg_replace('/\W/', '_', $scenario->getName()).'.png';
                                        $screenshotPath = $featureFolder. DIRECTORY_SEPARATOR .$_SERVER['RESULT_SCREENSHOT_FOLDER']. DIRECTORY_SEPARATOR .$screenshotFileName;
                                        if(file_exists($screenshotPath)){
                                            $printStep['screenshotPath'] =  $screenshotPath;
                                        }
                                        //URL
                                        $urlFileName = preg_replace('/\W/', '_', $scenario->getName()).'.url';
                                        $urlPath = $featureFolder. DIRECTORY_SEPARATOR .$_SERVER['RESULT_URL_FOLDER']. DIRECTORY_SEPARATOR .$urlFileName;
                                        if(file_exists($urlPath)){
                                            $printStep['urlPath'] =  file_get_contents($urlPath);
                                        }

                                    }
                                    $printScenario['steps'][] = $printStep;
                                }
                            }
                            $printFeature['scenarios'][]=$printScenario;
                        }
                    }
                    $printSuites['features'][]=$printFeature;
                }
                $printSuites['scenariosSuccess'] =  $nbScenarioSuccess;
                $printSuites['scenariosPending'] =  $nbScenarioPending;
                $printSuites['scenariosFailed'] =  $nbScenarioFailed;
                $print['suites'][]=$printSuites;
            }
        }


        return json_encode($print,True);
    }

    /**
     * Renders before a suite.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderBeforeSuite(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders after a suite.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderAfterSuite(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders before a feature.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderBeforeFeature(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders after a feature.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderAfterFeature(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders before a scenario.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderBeforeScenario(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders after a scenario.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderAfterScenario(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders before an outline.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderBeforeOutline(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders after an outline.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderAfterOutline(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders before a step.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderBeforeStep(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders after a step.
     *
     * @param BehatHTMLFormatter $obj
     *
     * @return string : HTML generated
     */
    public function renderAfterStep(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * To include CSS.
     *
     * @return string : HTML generated
     */
    public function getCSS(): string
    {
        return '';
    }

    /**
     * To include JS.
     *
     * @return string : HTML generated
     */
    public function getJS(): string
    {
        return '';
    }
}
