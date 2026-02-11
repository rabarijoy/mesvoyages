<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Visite;

class VisiteTest extends TestCase
{
    public function testGetDatecreationString() {
        $visite = new Visite();
        $visite->setDatecreation(new \DateTime('2026-01-01'));
        $this->assertEquals('01/01/2026', $visite->getDatecreationString());
    }
}