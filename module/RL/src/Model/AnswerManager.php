<?php
namespace RL\Model;

use RL\Model\ActiveQuestion;

class AnswerManager
{

    private $questionId;
    private $tries;
    private $direction;
    private $lastActiveQuestion;
    private $activeQuestion;

    private $table;

    private $inputFilter;

    public function __construct(QuestionTable $table)
    {
       $this->table = $table;
    }

    public function checkAnswer($answer, $doNotKnow)
    {
       $activeQuestion = $this->getActiveQuestion();

       if ($doNotKnow){
           $activeQuestion->doNotKnow();
       }
       else {
           $correct = $activeQuestion->check($answer);
       }

       if( $correct){

          $this->reset();
          return true;

       }
       else {

          if ($activeQuestion->allowMoreTries()){
              return false;

          }
          else {
              $this->reset();
              return false;

          }
       }
    }

    private function reset() {
          $this->setLastActiveQuestion();
          $this->resetQuestionId();
          $this->resetDirection();
          $this->resetActiveQuestion();
          return $this;
    }

    private function exceptionIfNoAnswer($answer) {
        if (! $answer) {
          throw new \Exception('Answer is required');
        }
    }

    private function resetQuestionId() {
       $max = $this->table->countAll();
       $id = rand(1,$max);
       $this->setQuestionId($id);
       return $this;
    }

    private function resetActiveQuestion() {
      $this->activeQuestion="";
    }

    private function incrementTries() {
      
      $this->setTries($this->getTries()+1);
      return $this;
    }

    private function checkTriesOk() {
      return ($this->getTries() < 3) ? true : false;
    }

    private function getQuestion() {
            return $this->table->getQuestion($this->getQuestionId());
    }

    private function getQuestionId() 
    {
      if (!$this->questionId){
          $this->resetQuestionId();
      }
      return $this->questionId;
    }

    private function setQuestionId($questionId)
    {
      $this->questionId = $questionId;
      return $this;
    }

    private function getDirection() {
      if (! $this->direction) {
          $this->resetDirection();
      }
      return $this->direction;
    }

    private function setDirection($direction) {
      $this->direction = $direction;
      return $this;
    }

    private function resetDirection() {
      $this->setDirection(rand(0,1) ? 'AQ' : 'QA');
      return $this;
    }

    public function getLastActiveQuestion() {
        return $this->lastActiveQuestion;
    }

    private function setLastActiveQuestion() {
        $this->lastActiveQuestion = $this->getActiveQuestion();
        return $this;
    }

    public function setActiveQuestion(ActiveQuestion $activeQuestion) {
        $this->activeQuestion = $activeQuestion;
        return $this;
    }

    public function getActiveQuestion() {
        if (! $this->activeQuestion) {
            $this->activeQuestion = new ActiveQuestion();
               $this->activeQuestion->setQuestion($this->getQuestion())
              ->setDirection($this->getDirection());
        }
        return $this->activeQuestion;
    }

}

