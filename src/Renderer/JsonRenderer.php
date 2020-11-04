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
            'Date' => date('d/m/Y H:i:s'),
            'Features'=>[
                'Failed'=>count($obj->getFailedFeatures()),
                'Passed'=>count($obj->getPassedFeatures())
            ],
            'Scenarios'=>[
                'Failed'=>count($obj->getFailedScenarios()),
                'Pending'=>count($obj->getPendingScenarios()),
                'Passed'=>count($obj->getPassedScenarios())
            ],
            'Steps'=>[
                'Failed'=>count($obj->getFailedSteps()),
                'Skipped'=>count($obj->getSkippedSteps()),
                'Passed'=>count($obj->getPassedSteps())
            ]
        ];
        foreach($obj->getSuites() as $suites){
            /** @var $suites Suite */
            if($suites->getFeatures()){
                $print['Suites'][$suites->getName()] = [
                    'Name' =>$suites->getName()
                    ];
                foreach ($suites->getFeatures() as $feature){
                    /** @var $feature Feature */
                    $printFeature = [
                        'id' => $feature->getId(),
                        'name' => $feature->getName(),
                        'description' => $feature->getDescription(),
                        'passedClass' => $feature->getPassedClass(),
                    ];
                    foreach($feature->getTags() as $tag){
                        $printFeature['tags'][] = $tag;
                    }
                    foreach($feature->getScenarios() as $scenario){
                        /** @var $scenario Scenario */
                        $printScenario=array();
                        $scenarioId = $scenario->getId();
                        $printScenario[$scenarioId] = [
                            'id' => $scenarioId,
                            'name' => $scenario->getName(),
                            'ispassed' => $scenario->isPassed(),
                            'ispending' => $scenario->isPending(),
                            'tags' => $scenario->getTags(),
                        ];

                        foreach($scenario->getSteps() as $step){
                            /** @var $step Step */
                            $printScenario[$scenarioId]['step'][] = [
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
                        }
                        $printFeature['scenarios']=$printScenario;
                    }
                    $print['Suites'][$suites->getName()]=$printFeature;
                }
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
