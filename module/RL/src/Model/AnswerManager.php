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
       # die('checkAnswer');
       if( $this->check($this->getQuestion(), $answer)){
          # die('Correct');
          $this->resetTries();
          $this->resetQuestionId();
          return true;
       }
       else {
          # die('Incorrect');
          $this->incrementTries(); 
          if ( $this->checkTriesOk()){
              return false;
          }
          else {
              $this->resetTries();
              $this->resetQuestionId();
              return false;
          }
       }
    }

    private function check(Question $question, $answer)
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

    private function resetQuestionId() {
       $id = rand(1,5);
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
      return $this->tries;
    }

    public function setTries($tries)
    {
      $this->tries = $tries;
      return $this;
    }
}

