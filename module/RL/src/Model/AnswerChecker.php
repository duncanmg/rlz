<?php
namespace RL\Model;

class AnswerChecker
{
    public $id;
    public $question;
    public $answer;
    public $aq_enabled_yn;

    private $inputFilter;

    public function __construct()
    {
    }

    public function check(Question $question, $answer)
    {
       error_log(print_r([$question, $answer],true));
       $this->exceptionIfNoAnswer($answer);

       return ($question->answer == $answer) ? true : false;
    }

    private function exceptionIfNoAnswer($answer) {
        if (! $answer) {
          throw new \Exception('Answer is required');
        }
    }

}

