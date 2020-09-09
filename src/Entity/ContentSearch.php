<?php


namespace App\Entity;

class ContentSearch
{
    /**
     * @var Category|null
     */
    private $category;

    /**
     * @var string|null
     */
    private $text;

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
