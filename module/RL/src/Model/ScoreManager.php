<?php
namespace RL\Model;

use RL\Model\UserQuestion;
use Exception;

class ScoreManager
{

    private $table;
    private $userId;
    private $question;
    private $userQuestion;
    private $direction;

    private $inputFilter;

    public function __construct(UserQuestionTable $table)
    {
       $this->table = $table;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    private function getUserId()
    {
        return $this->userId;
    }

    private function getActiveQuestion()
    {
        return $this->question;
    }

    public function setActiveQuestion(ActiveQuestion $question) {
        $this->question = $question;
        try {
            $userQuestion = $this->table->getUserQuestion($this->getUserId(), 
                                $this->getActiveQuestion()->getQuestion()->id);
        }
        catch( \Exception $e) {
            if ($e->getCode() == 1000) {
                 $userQuestion=new UserQuestion();
                 $userQuestion->exchangeArray([ 'user_id' => $this->getUserId(), 
                                    'question_id' => $this->getActiveQuestion()->getQuestion()->id]);
            }
            else {
                throw $e;
            }
        }
        $this->setUserQuestion($userQuestion);
        return $this;
    }

    private function setUserQuestion($userQuestion) {
        $this->userQuestion = $userQuestion;
        return $this;
    }

    private function getUserQuestion() {
        return $this->userQuestion;
    }

    public function update() {
        $aq = $this->getActiveQuestion();
        $status = $aq->getStatus();

        switch ($status) {
            case 'correct':
                $this->incrementScore();
                break;
            case 'incorrect':
                $this->decrementScore();
                break;
            default:
                throw new \Exception('ActiveQuestion is at the wrong status ' . $status);
        }
        $this->persist();
    }

    private function incrementScore() {
      $aq = $this->getActiveQuestion();
      if ($aq->getDirection() == 'QA')
      {
          $this->getUserQuestion()->score_qa = $this->getUserQuestion()->score_qa + 1;
      }
      else {
          $this->getUserQuestion()->score_aq = $this->getUserQuestion()->score_aq + 1;
      }
    }

    private function decrementScore() {
      $aq = $this->getActiveQuestion();
      if ($aq->getDirection() == 'QA')
      {
          $this->getUserQuestion()->score_qa = $this->getUserQuestion()->score_qa - 1;
      }
      else {
          $this->getUserQuestion()->score_aq = $this->getUserQuestion()->score_aq - 1;
      }
    }

    private function persist()
    {
        $this->table->saveUserQuestion($this->getUserQuestion());
    }

    public function getQAScore($direction) {
        return $this->getUserQuestion()->score_qa;
    }

    public function getAQScore($direction) {
        return $this->getUserQuestion()->score_aq;
    }

}

