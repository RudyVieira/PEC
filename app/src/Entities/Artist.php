<?php

namespace App\Entities;

use App\Lib\Entities\AbstractEntity;

class Artist extends AbstractEntity {
    public int $id;
    public string $name;

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

}