<?php
namespace RL\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Question implements InputFilterAwareInterface
{
    public $id;
    public $question;
    public $answer;
    public $aq_enabled_yn;
    public $source_id;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->question = !empty($data['question']) ? $data['question'] : null;
        $this->answer  = !empty($data['answer']) ? $data['answer'] : null;
        $this->aq_enabled_yn  = !empty($data['aq_enabled_yn']) ? $data['aq_enabled_yn'] : 'Y';
        $this->source_id = !empty($data['source_id']) ? $data['source_id'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'question' => $this->question,
            'answer'  => $this->answer,
            'aq_enabled_yn'  => $this->aq_enabled_yn,
            'source_id' => $this->source_id,
        ];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'question',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'answer',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'aq_enabled_yn',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'source_id',
            'required' => true,
            'validators' => [],
            'filters' => [
                 ['name' => ToInt::class],
              
            ],
        ]);
    
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}

