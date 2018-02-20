<?php

namespace RL\Form;

use Zend\Form\Form;

class QuestionForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('question');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'question',
            'type' => 'text',
            'options' => [
                'label' => 'Question',
            ],
        ]);
        $this->add([
            'name' => 'answer',
            'type' => 'text',
            'options' => [
                'label' => 'Answer',
            ],
        ]);
        $this->add([
            'name' => 'score_qa',
            'type' => 'text',
            'options' => [
                'label' => 'Score QA',
            ],
        ]);
        $this->add([
            'name' => 'score_aq',
            'type' => 'text',
            'options' => [
                'label' => 'Score AQ',
            ],
        ]);
        $this->add([
            'name' => 'aq_enabled_yn',
            'type' => 'text',
            'options' => [
                'label' => 'AQ Enabled Y/N',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
