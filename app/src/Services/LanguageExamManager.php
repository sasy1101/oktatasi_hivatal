<?php

namespace App\Services;

use App\DTO\LanguageExam;

class LanguageExamManager
{
    const ARRAY_KEY = 'tobbletpontok';
    const LANGUAGE_MAP = [
        'angol' => 'en',
        'német' => 'de',
    ];
    const pointMap = [
        'B2' => 28,
        'C1' => 40,
    ];
    private $languageExams;
    public function __construct(array $extraPoints) {
        $languageExams = [];
        foreach ($extraPoints as $extraPoint) {
            $languageExams[] = LanguageExam::create($extraPoint['kategoria'], $extraPoint['tipus'], $extraPoint['nyelv']);
        }

        $this->languageExams = $languageExams;
    }

    public function calculateExtraPoints() {
        $maxResultByLanguage = [];
        $sumExtraPoints = 0;
        /** @var LanguageExam $languageExam */
        foreach ($this->languageExams as $languageExam) {
            if(!isset($maxResultByLanguage[self::LANGUAGE_MAP[$languageExam->getLanguage()]])) {
                $maxResultByLanguage[self::LANGUAGE_MAP[$languageExam->getLanguage()]] = self::pointMap[$languageExam->getType()];
            } elseif($maxResultByLanguage[self::LANGUAGE_MAP[$languageExam->getLanguage()]] < self::pointMap[$languageExam->getType()]) {
                $maxResultByLanguage[self::LANGUAGE_MAP[$languageExam->getLanguage()]] = self::pointMap[$languageExam->getType()];
            }
        }

        foreach ($maxResultByLanguage as $extraPoint) {
            $sumExtraPoints += $extraPoint;
        }

        return $sumExtraPoints;
    }
}