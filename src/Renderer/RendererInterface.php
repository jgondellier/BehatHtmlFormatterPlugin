<?php

namespace gondellier\BehatHTMLFormatter\Renderer;

use gondellier\BehatHTMLFormatter\Formatter\BehatHTMLFormatter;

interface RendererInterface
{
    /**
     * Renders before an exercice.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderBeforeExercise(BehatHTMLFormatter $obj):string;

    /**
     * Renders after an exercice.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderAfterExercise(BehatHTMLFormatter $obj):string;

    /**
     * Renders before a suite.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderBeforeSuite(BehatHTMLFormatter $obj):string;

    /**
     * Renders after a suite.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderAfterSuite(BehatHTMLFormatter $obj):string;

    /**
     * Renders before a feature.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderBeforeFeature(BehatHTMLFormatter $obj):string;

    /**
     * Renders after a feature.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderAfterFeature(BehatHTMLFormatter $obj):string;

    /**
     * Renders before a scenario.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderBeforeScenario(BehatHTMLFormatter $obj):string;

    /**
     * Renders after a scenario.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderAfterScenario(BehatHTMLFormatter $obj):string;

    /**
     * Renders before an outline.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderBeforeOutline(BehatHTMLFormatter $obj):string;

    /**
     * Renders after an outline.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderAfterOutline(BehatHTMLFormatter $obj):string;

    /**
     * Renders before a step.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderBeforeStep(BehatHTMLFormatter $obj):string;

    /**
     * Renders after a step.
     *
     * @param BehatHTMLFormatter $obj : BehatHTMLFormatter object
     *
     * @return string : HTML generated
     */
    public function renderAfterStep(BehatHTMLFormatter $obj):string;

    /**
     * To include CSS.
     *
     * @return string : HTML generated
     */
    public function getCSS():string;

    /**
     * To include JS.
     *
     * @return string : HTML generated
     */
    public function getJS():string;
}
