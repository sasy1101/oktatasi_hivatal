<?php

namespace App\DTO;

class LanguageExam
{
    private string $category;
    private string $type;
    private string $language;

    private function __construct(string $category, string $type, string $language) {
        $this->category = $category;
        $this->type = $type;
        $this->language = $language;
    }

    public static function create(string $category, string $type, string $language): LanguageExam
    {
        return new self($category, $type, $language);
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}