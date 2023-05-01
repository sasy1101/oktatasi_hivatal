<?php

namespace App\DTO;

use http\Exception\InvalidArgumentException;

class ExaminationSubject extends Subject
{
    const NEED_TO_EXAM = [
        'magyar nyelv és irodalom',
        'történelem',
        'matematika'
    ];
    private bool $highLevel;
    private int $resultInPoints;
    private bool $fault;
    private bool $needToExam;

    private function __construct(string $subject, string $level, int $resultInPercent)
    {
        parent::__construct($subject, $level);
        $this->highLevel = $level == 'emelt';
        $this->resultInPoints = $resultInPercent;
        $this->fault = ($resultInPercent < 20);
        $this->needToExam = in_array($subject, self::NEED_TO_EXAM);
    }

    public static function createFromArray(array $input)
    {
        if(empty($input['nev'])) {
            throw new \InvalidArgumentException('Hiányzik az érettségizett tantárgy neve.');
        }
        if(empty($input['tipus'])) {
            throw new InvalidArgumentException('Hiányzik az érettségizett tárgy típusa.');
        }
        if(empty($input['eredmeny'])) {
            throw new InvalidArgumentException('Hiányzik az érettségizett tárgy eredménye.');
        }
        preg_match('/[0-9]+/', $input['eredmeny'], $matches);
        if(empty($matches)) {
            throw new InvalidArgumentException('Nem megfelelő formátumú az eredmény mező.');
        }

        return new self($input['nev'], $input['tipus'], (int) $matches[0]);
    }

    /**
     * @return bool
     */
    public function isHighLevel(): bool
    {
        return $this->highLevel;
    }

    /**
     * @return int
     */
    public function getResultInPoints(): int
    {
        return $this->resultInPoints;
    }

    /**
     * @return bool
     */
    public function isFault(): bool
    {
        return $this->fault;
    }

    /**
     * @return bool
     */
    public function isNeedToExam(): bool
    {
        return $this->needToExam;
    }
}