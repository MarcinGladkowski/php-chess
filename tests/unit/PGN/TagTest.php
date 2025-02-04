<?php

namespace Chess\Tests\Unit\PGN;

use Chess\PGN\Tag;
use Chess\Tests\AbstractUnitTestCase;

class TagTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function Foo_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        Tag::validate('Foo');
    }

    /**
     * @test
     */
    public function Event_Vladimir_Dvorkovich_Cup()
    {
        $tag = Tag::validate('[Event "Vladimir Dvorkovich Cup"]');

        $this->assertSame('Event', $tag->name);
        $this->assertSame('Vladimir Dvorkovich Cup', $tag->value);
    }

    /**
     * @test
     */
    public function Site_Saint_Louis_USA()
    {
        $tag = Tag::validate('[Site "Saint Louis USA"]');

        $this->assertSame('Site', $tag->name);
        $this->assertSame('Saint Louis USA', $tag->value);
    }

    /**
     * @test
     */
    public function Date_2018_05_10()
    {
        $tag = Tag::validate('[Date "2018.05.10"]');

        $this->assertSame('Date', $tag->name);
        $this->assertSame('2018.05.10', $tag->value);
    }

    /**
     * @test
     */
    public function Round_9_6()
    {
        $tag = Tag::validate('[Round "9.6"]');

        $this->assertSame('Round', $tag->name);
        $this->assertSame('9.6', $tag->value);
    }

    /**
     * @test
     */
    public function White_Kantor_Gergely()
    {
        $tag = Tag::validate('[White "Kantor, Gergely"]');

        $this->assertSame('White', $tag->name);
        $this->assertSame('Kantor, Gergely', $tag->value);
    }

    /**
     * @test
     */
    public function Black_Gelfand_Boris()
    {
        $tag = Tag::validate('[Black "Gelfand, Boris"]');

        $this->assertSame('Black', $tag->name);
        $this->assertSame('Gelfand, Boris', $tag->value);
    }

    /**
     * @test
     */
    public function Result_12_12()
    {
        $tag = Tag::validate('[Result "1/2-1/2"]');

        $this->assertSame('Result', $tag->name);
        $this->assertSame('1/2-1/2', $tag->value);
    }

    /**
     * @test
     */
    public function WhiteElo_2579()
    {
        $tag = Tag::validate('[WhiteElo "2579"]');

        $this->assertSame('WhiteElo', $tag->name);
        $this->assertSame('2579', $tag->value);
    }

    /**
     * @test
     */
    public function BlackElo_2474()
    {
        $tag = Tag::validate('[BlackElo "2474"]');

        $this->assertSame('BlackElo', $tag->name);
        $this->assertSame('2474', $tag->value);
    }

    /**
     * @test
     */
    public function ECO_D35()
    {
        $tag = Tag::validate('[ECO "D35"]');

        $this->assertSame('ECO', $tag->name);
        $this->assertSame('D35', $tag->value);
    }

    /**
     * @test
     */
    public function EventDate_2017_12_17()
    {
        $tag = Tag::validate('[EventDate "2017.12.17"]');

        $this->assertSame('EventDate', $tag->name);
        $this->assertSame('2017.12.17', $tag->value);
    }
}
