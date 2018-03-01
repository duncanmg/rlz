<?php
namespace RL\Model;

class AnswerManager
{

    private $questionId;
    private $tries;
    private $table;

    private $inputFilter;

    public function __construct(QuestionTable $table)
    {
       $this->table = $table;
    }

    public function checkAnswer($answer)
    {
       $this->getQuestion();

       $check = ($this->getDirection() == 'QA') ? $this->checkQA($this->getQuestion(), $answer)
         : $this->checkAQ($this->getQuestion(), $answer);

       if( $check){

          $this->reset();
          return true;

       }
       else {

          $this->incrementTries(); 
          if ($this->checkTriesOk()){
              return false;

          }
          else {

              $this->reset();
              return false;

          }
       }
    }

    private function reset() {
          $this->resetTries();
          $this->resetQuestionId();
          $this->resetDirection();
          return $this;
    }

    private function checkQA(Question $question, $answer)
    {
       error_log(print_r([$question, $answer],true));
       $this->exceptionIfNoAnswer($answer);
       return ($question->answer == $answer) ? true : false;
    }

    private function checkAQ(Question $question, $answer)
    {
       error_log(print_r([$question, $answer],true));
       $this->exceptionIfNoAnswer($answer);
       return ($question->question == $answer) ? true : false;
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

    private function incrementTries() {
      
      $this->setTries($this->getTries()+1);
      return $this;
    }

    private function checkTriesOk() {
      return ($this->getTries() < 3) ? true : false;
    }

    private function resetTries() {
      $this->setTries(0);
    }

    private function getQuestion() {
            return $this->table->getQuestion($this->getQuestionId());
    }

    public function getQuestionId() 
    {
      if (!$this->questionId){
          $this->resetQuestionId();
      }
      return $this->questionId;
    }

    public function setQuestionId($questionId)
    {
      $this->questionId = $questionId;
      return $this;
    }

    public function getTries() 
    {
      return $this->tries ? $this->tries : 0;
    }

    public function setTries($tries)
    {
      $this->tries = $tries;
      return $this;
    }

    public function getDirection() {
      if (! $this->direction) {
          $this->resetDirection();
      }
      return $this->direction;
    }

    public function setDirection($direction) {
      $this->direction = $direction;
      return $this;
    }

    private function resetDirection() {
      $this->setDirection(rand(0,1) ? 'AQ' : 'QA');
      return $this;
    }

}

