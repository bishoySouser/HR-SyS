<?php

namespace App\Services\Interfaces;

interface VoteInterface
{
    public function castVote(int $voterId, int $candidateId): bool;
    public function getResults(): array;
}
