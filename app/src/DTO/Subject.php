<?php

namespace App\DTO;

class Subject
{
    private string $subject;
    private ?string $level;

    protected function __construct(string $subject, ?string $level) {
        $this->subject = $subject;
        $this->level = $level;
    }

    public static function create(string $subject, ?string $level = null) {
        return new self($subject, $level);
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string|null
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }
}