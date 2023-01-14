<?php

namespace App\Model\Discogs;

use Symfony\Component\Serializer\Annotation\SerializedPath;

class Note
{
    #[SerializedPath('[field_id]')]
    private int $fieldId;
    private string $value;

    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    public function setFieldId(int $fieldId): void
    {
        $this->fieldId = $fieldId;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
