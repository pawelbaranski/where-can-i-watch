<?php

namespace tests\integration\AppBundle\Form\Type;


use tests\FormTypeTestCase;
use WhereCanIWatch\AppBundle\Form\Type\SearchType;

class SearchTypeTest extends FormTypeTestCase
{
    protected function formTypeClass()
    {
        return SearchType::class;
    }

    /**
     * @test
     * @dataProvider blankValues
     */
    public function isInvalidIfQueryIsBlank($blank)
    {
        $this->form->submit([['query' => $blank]]);

        $this->assertFalse($this->form->isValid());
    }

    /** @test */
    public function isInvalidIfQueryIsNotSet()
    {
        $this->form->submit([]);

        $this->assertFalse($this->form->isValid());
    }

    /** @test */
    public function isInvalidIfQueryIsShorterThanThreeCharacters()
    {
        $this->form->submit(['query' => 'ab']);

        $this->assertFalse($this->form->isValid());
    }

    /** @test */
    public function isValidIfQueryHasThreeCharacters()
    {
        $this->form->submit(['query' => 'abc']);

        $this->assertTrue($this->form->isValid());
    }

    /** @test */
    public function isValidIfQueryHasMoreThanThreeCharacters()
    {
        $this->form->submit(['query' => 'some query']);

        $this->assertTrue($this->form->isValid());
    }

    public function blankValues()
    {
        return [
            [''],
            [null],
            [false]
        ];
    }
}