<?php
namespace RL\Model;

use Exception;

class SessionScoreManager
{

    private $sessionScore;

    public function __construct()
    {
        $this->sessionScore = new \ArrayObject([ 'asked' => 0, 'correct' => 0, 'incorrect' => 0 ]);
    }

    public function getSessionScore()
    {
        return $this->sessionScore;
    }

    public function setSessionScore(\ArrayObject $question) {
        $this->sessionScore = $sessionScore;
        return $this;
    }

    public function updateSessionScore(ActiveQuestion $activeQuestion) {
        $activeQuestion->getCorrect() ? $this->updateCorrect() 
          : $this->updateInCorrect();
        return $this;
    }

    private function updateCorrect() {
       $sc = $this->getSessionScore();
       $sc['asked'] += 1;
       $sc['correct'] += 1;
       $this->setSessionScore($sc);
       return $this;
    }

    private function updateInCorrect() {
       $sc = $this->getSessionScore();
       $sc['asked'] += 1;
       $sc['incorrect'] += 1;
       $this->setSessionScore($sc);
       return $this;
    }

}

