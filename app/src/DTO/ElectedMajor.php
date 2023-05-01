<?php

namespace App\DTO;

use http\Exception\InvalidArgumentException;

class ElectedMajor
{
    const KEY = 'valasztott-szak';
    public static $subjectMap = [
        [
            'university' => 'ELTE',
            'faculty' => 'IK',
            'major' => 'Programtervező informatikus',
            'requiredSubjects' => [
                ['subject' => 'matematika']
            ],
            'chosenSubjects' => [
                ['subject' => 'biológia'],
                ['subject' => 'fizika'],
                ['subject' => 'informatika'],
                ['subject' => 'kémia'],
            ]
        ],
        [
            'university' => 'PPKE',
            'faculty' => 'BTK',
            'major' => 'Anglisztika',
            'requiredSubjects' => [
                ['subject' => 'angol', 'level' => 'emelt']
            ],
            'chosenSubjects' => [
                ['subject' => 'angol'],
                ['subject' => 'fizika'],
                ['subject' => 'informatika'],
                ['subject' => 'kémia'],
            ]
        ],
    ];

    private string $university;
    private string $faculty;

    private string $major;

    private function __construct(string $university, string $faculty, string $major) {
        $this->university = $university;
        $this->faculty = $faculty;
        $this->major = $major;
    }

    public static function createFromArray(array $chosenMajor): ElectedMajor
    {
        if(empty($chosenMajor['egyetem'])) {
            throw new InvalidArgumentException('Hiányzik az választott egyetem.');
        }
        if(empty($chosenMajor['kar'])) {
            throw new InvalidArgumentException('Hiányzik a választott kar');
        }
        if(empty($chosenMajor['szak'])) {
            throw new InvalidArgumentException('Hiányzik a választott szak');
        }
        return new self($chosenMajor['egyetem'], $chosenMajor['kar'], $chosenMajor['szak']);
    }

    public function getRequiredSubjects(): array
    {
        $subjects = [];
        foreach (self::$subjectMap as $major) {
            if($major['university'] == $this->university && $major['faculty'] == $this->faculty && $major['major'] == $this->major) {
                foreach ($major['requiredSubjects'] as $subjectArray) {
                    $level = (!empty($subjectArray['level'])) ? $subjectArray['level'] : null;
                    $subjects[] = Subject::create($subjectArray['subject'], $level);
                }
            }
        }
        return $subjects;
    }

    public function getChosenSubjects(): array
    {
        $subjects = [];
        foreach (self::$subjectMap as $major) {
            if($major['university'] == $this->university && $major['faculty'] == $this->faculty && $major['major'] == $this->major) {
                foreach ($major['chosenSubjects'] as $subjectArray) {
                    $level = (!empty($subjectArray['level'])) ? $subjectArray['level'] : null;
                    $subjects[] = Subject::create($subjectArray['subject'], $level);
                }
            }
        }
        return $subjects;
    }
}