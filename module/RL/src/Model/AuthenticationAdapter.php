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
    private $isFrozen;
    private $status;
    
    public function _construct() {
    }

    #public function exchangeArray(array $data)
    #{
    #    $this->question     = !empty($data['question']) ? $data['question'] : null;
    #    $this->correct = !empty($data['correct']) ? $data['correct'] : false;
    #    $this->direction  = !empty($data['direction']) ? $data['direction'] : null;
    #    $this->tries  = !empty($data['tries']) ? $data['tries'] : 0;
    #    $this->isFrozen = false;
    #    $this->setStatus('new');
    #}

    public function getArrayCopy() {
        return [
            'question' => $this->getQuestionText(),
            'answer' => $this->getAnswerText(),
            'status' => $this->getStatus(),
            'direction' => $this->getDirection(),
        ];
    }

    public function getStatus() {
      return $this->status;    
    }
    
    public function check($answer) {
      if ($this->getAnswerText() == $answer) {
         $this->setStatus('correct');
      }
      else {
         $this->incrementTries();
         $this->setStatus($this->allowMoreTries() ? 'retry' : 'incorrect');
         return false;
      }
      return $this;
    }

    public function doNotKnow() {
      while ($this->allowMoreTries()) {
          $this->incrementTries();
      }
      $this->setStatus('incorrect');
    }

    public function getQuestion() {
      return $this->question;
    }

    public function setQuestion(Question $question) {
        $this->exceptionIfFrozen( $this->question);
        $this->question = $question;
        return $this;
    }

    public function getDirection() {
      return $this->direction;
    }

    public function setDirection($direction) {
      $this->exceptionIfFrozen( $this->direction);
      $this->direction = $direction;
      return $this;
    }

    public function getTries() {
      return $this->tries;
    }

    public function getAnswerText() {
      $q = $this->getQuestion();
      return ($this->getDirection() == 'QA') ? $q->answer : $q->question;
    }

    public function getQuestionText() {
      $q = $this->getQuestion();
      return ($this->getDirection() == 'QA') ? $q->question : $q->answer;
    }

    public function isFrozen() {
      return in_array($this->getStatus(), [ 'correct', 'wrong'])
              ? true : false;
    }


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    private function getCorrect() {
      return $this->correct;
    }

    private function incrementTries() {
       $this->setTries($this->getTries() + 1);
       $this->allowMoreTries();
       return $this;
    }

    private function allowMoreTries() {
       if ($this->getTries() >= 3) {
         $this->freeze();
         return false;
       }
       return true;
    }

    private function freeze() {
      $this->isFrozen = true;
      return $this;
    }

    private function exceptionIfFrozen($test) {
      if ($test) {
         throw new \Exception("This ActiveQuestion is frozen");
      }
    }

    private function setCorrect($correct) {
      $this->correct = $correct;
      $this->freeze();
      return $this;
    }
    
    private function setTries($tries) {
      $this->tries = $tries;
      return $this;
    }
 
    private function setStatus($status) {
        $validStatuses = [ 'new', 'correct', 'retry', 'incorrect'];
        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Invalid status ' . $status);
        }
        $this->status = $status;
        return $this;
    }
}

