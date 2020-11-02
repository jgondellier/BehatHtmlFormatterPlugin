<?php
/**
 * Behat2 renderer for Behat report.
 *
 * @author DaSayan <glennwall@free.fr>
 */

namespace gondellier\BehatHTMLFormatter\Renderer;

use gondellier\BehatHTMLFormatter\Formatter\BehatHTMLFormatter;

class MinimalRenderer
{
    private $extension = 'csv';
    private $rendererList;

    public function __construct()
    {
    }

    public function getExtension($renderer): string
    {
        return $this->extension;
    }

    /**
     * Renders before an exercice.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderBeforeExercise(BehatHTMLFormatter $obj): string
    {
        return '';
    }

    /**
     * Renders after an exercice.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderAfterExercise(BehatHTMLFormatter $obj): string
    {
        $strFeatPassed = count($obj->getPassedFeatures());
        $strFeatFailed = count($obj->getFailedFeatures());
        $strScePassed = count($obj->getPassedScenarios());
        $strScePending = count($obj->getPendingScenarios());
        $strSceFailed = count($obj->getFailedScenarios());
        $strStepsPassed = count($obj->getPassedSteps());
        $strStepsPending = count($obj->getPendingSteps());
        $strStepsSkipped = count($obj->getSkippedSteps());
        $strStepsFailed = count($obj->getFailedSteps());

        $featTotal = (count($obj->getFailedFeatures()) + count($obj->getPassedFeatures()));
        $sceTotal = (count($obj->getFailedScenarios()) + count($obj->getPendingScenarios()) + count($obj->getPassedScenarios()));
        $stepsTotal = (count($obj->getFailedSteps()) + count($obj->getPassedSteps()) + count($obj->getSkippedSteps()) + count($obj->getPendingSteps()));

        $print = $featTotal . ',' . $strFeatPassed . ',' . $strFeatFailed . "\n";
        $print .= $sceTotal . ',' . $strScePassed . ',' . $strScePending . ',' . $strSceFailed . "\n";
        $print .= $stepsTotal . ',' . $strStepsPassed . ',' . $strStepsFailed . ',' . $strStepsSkipped . ',' . $strStepsPending . "\n";
        $print .= $obj->getTimer() . ',' . $obj->getMemory() . "\n";

        return $print;
    }

    /**
     * Renders before a suite.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj  : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
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
     * @param BehatHTMLFormatter $obj  : BehatHTMLFormatter object
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
