<?php
namespace RL\Model;

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

    public function setSessionScore(\ArrayObject $sessionScore) {
        $this->sessionScore = $sessionScore;
        return $this;
    }

    public function updateSessionScore(ActiveQuestion $activeQuestion) {
        $status = $activeQuestion->getStatus();
        switch ($status) {
            case 'correct':
                $this->updateCorrect();
                break;
            case 'incorrect':
                $this->updateInCorrect();
                break;
            default :
                throw new \Exception('ActiveQuestion is at wrong state.' . $status);
        }

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

