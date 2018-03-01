<?php
namespace RL\Model;

use RL\Model\Question;

class ActiveQuestion
{
    private $question;
    private $correct;
    private $direction;
    private $tries;
    private $answer;

    public function _construct() {
    }

    public function exchangeArray(array $data)
    {
        $this->question     = !empty($data['question']) ? $data['question'] : null;
        $this->correct = !empty($data['correct']) ? $data['correct'] : false;
        $this->direction  = !empty($data['direction']) ? $data['direction'] : null;
        $this->tries  = !empty($data['tries']) ? $data['tries'] : 0;
    }

    public function getArrayCopy()
    {
        return [
            'question'     => $this->question,
            'correct' => $this->correct,
            'direction'  => $this->direction,
            'tries'  => $this->tries,
        ];
    }

    public function check($answer) {
      if ($this->getAnswerText() == $answer) {
         $this->setCorrect(true);
         return true;
      }
      else {
         $this->incrementTries();
         return false;
      }
    }
    public function getQuestion() {
      return $this->question;
    }

    public function setQuestion(Question $question) {
        $this->question = $question;
        return $this;
    }

    public function getCorrect() {
      return $this->correct;
    }

    public function setCorrect($correct) {
      $this->correct = $correct;
      return $this;
    }

    public function getDirection() {
      return $this->direction;
    }

    public function setDirection($direction) {
      $this->direction = $direction;
      return $this;
    }

    public function getTries() {
      return $this->tries;
    }

    private function setTries($tries) {
      $this->tries = $tries;
      return $this;
    }

    public function getAnswerText() {
      $q = $this->getQuestion();
      return ($this->getDirection() == 'QA') ? $q->answer : $q->question;
    }

    public function getQuestionText() {
      $q = $this->getQuestion();
      return ($this->getDirection() == 'QA') ? $q->question : $q->answer;
    }

    public function incrementTries() {
       $this->setTries($this->getTries() + 1);
    }

    public function allowMoreTries() {
       return ($this->getTries() >= 3) ? false : true;
    }

}

