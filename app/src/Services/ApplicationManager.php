<?php

namespace App\Services;

use App\DTO\ApplicationPoints;
use App\DTO\ElectedMajor;
use App\DTO\ExaminationSubject;
use App\Exception\ApplicationException;
use Doctrine\DBAL\Driver\Exception;

class ApplicationManager
{
    public function calculatePointsFromArray(array $person)
    {
            if(empty($person[ElectedMajor::KEY])) {
                throw new ApplicationException('hiba, Hiányzik a választott egyetem/kar/szak.');
            }
            if(empty($person[Examination::ARRAY_KEY])) {
                throw new ApplicationException('hiba, Hiányoznak az érettségi eredmények');
            }

            //@TODO: Ezzel lehet csinálni valamit
            try {
                $electedMajor = ElectedMajor::createFromArray($person[ElectedMajor::KEY]);
            } catch (Exception $e) {
                throw new ApplicationException('hiba, ' . $e->getMessage());
            }

            $examinationSubjects = [];
            foreach ($person[Examination::ARRAY_KEY] as $subject) {
                try {
                    $examinationSubjects[] = ExaminationSubject::createFromArray($subject);
                } catch (Exception $e) {
                    throw new ApplicationException('hiba, ' . $e->getMessage());
                }
            }
            try {
                $examination = Examination::createFromArray($examinationSubjects, $electedMajor);
            } catch (ApplicationException $e) {
                throw new ApplicationException($e->getMessage());
            } catch (\Exception $e) {
                throw new ApplicationException('hiba, ' . $e->getMessage());
            }
            $basePoints = $examination->getBasePoints();
            $allExtraPoints = 0;
            $allExtraPoints += $examination->getExtraPoints();
            $languageExamManager = new LanguageExamManager($person[LanguageExamManager::ARRAY_KEY]);
            $allExtraPoints += $languageExamManager->calculateExtraPoints();

            if($allExtraPoints > 100) {
                $allExtraPoints = 100;
            }
            if($basePoints > 400) {
                $basePoints = 400;
            }
            return ApplicationPoints::create($basePoints, $allExtraPoints, true);
    }
}