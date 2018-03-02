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
            'attributes' => [
                'readonly' => true,
            ],
        ]);
        $this->add([
            'name' => 'answer',
            'type' => 'text',
            'options' => [
                'label' => 'Answer',
            ],
            'attributes' => [
                'required' => true,
                'autocomplete' => 'off',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Submit Answer',
                'id'    => 'submitbutton',
            ],
        ]);
        $this->add([
            'name' => 'donotknow',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Don\'t Know',
                'id'    => 'donotknowbutton',
                'onclick' => 'return checkForm()'
            ],
        ]);

    }
}
