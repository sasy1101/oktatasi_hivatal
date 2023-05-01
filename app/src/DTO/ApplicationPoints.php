<?php

namespace App\DTO;

class ApplicationPoints
{
    private int $basicPoints;
    private int $extraPoints;

    private function __construct(int $basicPoints, int $extraPoints)
    {
        $this->basicPoints = $basicPoints;
        $this->extraPoints = $extraPoints;
    }

    public function __toString()
    {
        return sprintf('%u (%u alappont + %u többletpont)', $this->getFullPoints(), $this->getBasicPoints(), $this->getExtraPoints());
    }

    public static function create(int $basicPoints, int $extraPoints): ApplicationPoints
    {
        return new self($basicPoints, $extraPoints);
    }

    /**
     * @return int
     */
    public function getBasicPoints(): int
    {
        return $this->basicPoints;
    }

    /**
     * @return int
     */
    public function getExtraPoints(): int
    {
        return $this->extraPoints;
    }

    public function getFullPoints(): int
    {
        return $this->basicPoints + $this->extraPoints;
    }
}