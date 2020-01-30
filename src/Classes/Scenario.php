<?php

namespace gondellier\BehatHTMLFormatter\Classes;

/**
 * Class Scenario
 * @package gondellier\BehatHTMLFormatter\Classes
 */
class Scenario
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $line;
    /**
     * @var
     */
    private $tags;
    /**
     * @var
     */
    private $loopCount;

    /**
     * @var string
     */
    private $screenshotName;
    /**
     * @var
     */
    private $url;

    /**
     * @var bool
     */
    private $passed;

    /**
     * @var bool
     */
    private $pending;

    /**
     * @var Step[]
     */
    private $steps;
    /**
     * @var
     */
    private $screenshotPath;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getScreenshotName()
    {
        return $this->screenshotName;
    }

    /**
     * @param $scenarioName
     */
    public function setScreenshotName($scenarioName)
    {
        $this->screenshotName = preg_replace('/\W/', '', $scenarioName).'.png';
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getLoopCount(): int
    {
        return $this->loopCount;
    }

    /**
     * @param int $loopCount
     */
    public function setLoopCount($loopCount): void
    {
        $this->loopCount = $loopCount;
    }

    /**
     * @return mixed
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param mixed $line
     */
    public function setLine($line): void
    {
        $this->line = $line;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return bool
     */
    public function isPassed()
    {
        return $this->passed;
    }

    /**
     * @param bool $passed
     */
    public function setPassed($passed)
    {
        $this->passed = $passed;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->pending;
    }

    /**
     * @param bool $pending
     */
    public function setPending($pending)
    {
        $this->pending = $pending;
    }

    /**
     * @return Step[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param Step[] $steps
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;
    }

    /**
     * @param Step $step
     */
    public function addStep($step)
    {
        $this->steps[] = $step;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return float|int
     */
    public function getLoopSize()
    {
        //behat
        return $this->loopCount > 0 ? sizeof($this->steps) / $this->loopCount : sizeof($this->steps);
    }

    /**
     * @param $string
     */
    public function setScreenshotPath($string)
    {
        $this->screenshotPath = $string;
    }

    /**
     * @return mixed
     */
    public function getScreenshotPath()
    {
        return $this->screenshotPath;
    }

    /**
     * Gets relative path for screenshot.
     *
     * @return bool|string
     */
    public function getRelativeScreenshotPath()
    {
        if (!file_exists($this->screenshotPath)) {
            return false;
        }

        return '.'.substr($this->screenshotPath, strpos($this->screenshotPath, '/assets/screenshots'));
    }
}
