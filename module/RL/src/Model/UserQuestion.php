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

class UserQuestion implements InputFilterAwareInterface
{
    public $id;
    public $question_id;
    public $user_id;
    public $score_qa;
    public $score_aq;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->question_id = !empty($data['question_id']) ? $data['question_id'] : null;
        $this->user_id  = !empty($data['user_id']) ? $data['user_id'] : null;
        $this->score_qa  = !empty($data['score_qa']) ? $data['score_qa'] : 0;
        $this->score_aq  = !empty($data['score_aq']) ? $data['score_aq'] : 0;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'question_id' => $this->question_id,
            'user_id'  => $this->user_id,
            'score_qa'  => $this->score_qa,
            'score_aq'  => $this->score_aq,
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
            'name' => 'question_id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'user_id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'score_qa',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'score_aq',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}

