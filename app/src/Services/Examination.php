<?php

namespace App\Services;

use App\DTO\ElectedMajor;
use App\DTO\ExaminationSubject;
use App\DTO\Subject;
use App\Exception\ApplicationException;

class Examination
{
    const ARRAY_KEY = 'erettsegi-eredmenyek';
    const MINIMAL_NUMBER_OF_NEEDED_SUBJECTS = 3;
    private int $basePoints;
    private int $extraPoints;

    private function __construct(array $results, ElectedMajor $electedMajor)
    {
        $neededSubjectCounter = 0;
        $maxPointFromRequiredSubjects = 0;
        $maxPointFromChosenSubject = 0;
        $extraPoints = 0;


        /** @var ExaminationSubject $result */
        foreach ($results as $result) {
            if($result->isFault()) {
                throw new ApplicationException(sprintf('hiba, nem lehetséges a pontszámítás a %s tárgyból elért 20%% alatti eredmény miatt', $result->getSubject()));
            }
            if($result->getLevel() === 'emelt') {
                $extraPoints += 50;
            }

            if($result->isNeedToExam()) {
                $neededSubjectCounter++;
            }
            /** @var Subject $requiredSubject */
            foreach ($electedMajor->getRequiredSubjects() as $requiredSubject) {
                if(
                    $requiredSubject->getSubject() == $result->getSubject()
                    && ($requiredSubject->getLevel() === null || $requiredSubject->getLevel() == $result->getLevel())
                    && $result->getResultInPoints() > $maxPointFromRequiredSubjects
                ) {
                    $maxPointFromRequiredSubjects = $result->getResultInPoints();
                }
            }

            foreach ($electedMajor->getChosenSubjects() as $chosenSubject) {
                if(
                    $chosenSubject->getSubject() == $result->getSubject()
                    && ($chosenSubject->getLevel() === null || $chosenSubject->getLevel() == $result->getLevel())
                    && $result->getResultInPoints() > $maxPointFromChosenSubject
                ) {
                    $maxPointFromChosenSubject = $result->getResultInPoints();
                }
            }
        }
        if($neededSubjectCounter < self::MINIMAL_NUMBER_OF_NEEDED_SUBJECTS) {
            throw new ApplicationException('hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt');
        }

        $this->basePoints = ($maxPointFromRequiredSubjects + $maxPointFromChosenSubject) * 2;
        $this->extraPoints = $extraPoints;
    }

    public static function createFromArray(array $results, ElectedMajor $electedMajor)
    {
        return new self($results, $electedMajor);
    }

    /**
     * @return int
     */
    public function getBasePoints(): int
    {
        return $this->basePoints;
    }

    public function getExtraPoints(): int
    {
        return $this->extraPoints;
    }
}