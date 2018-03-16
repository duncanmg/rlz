<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use RL\Model\QuestionTable;
use RL\Model\AnswerManager;
use RL\Model\ScoreManager;
use RL\Model\SessionScoreManager;
use Zend\Session\Container;

use RL\Form\QuestionForm;

class QuestionController extends AbstractActionController
{

    private $table;
    private $answerManager;
    private $sessionContainer;
    private $scoreManager;
    private $sessionScoreManager;

    public function __construct(QuestionTable $table, AnswerManager $answerManager, 
       Container $sessionContainer, ScoreManager $scoreManager,
       SessionScoreManager $sessionScoreManager)
    {
        $this->table = $table;
        $this->answerManager = $answerManager;
        $this->sessionContainer = $sessionContainer;
        $this->scoreManager = $scoreManager;
        $this->sessionScoreManager = $sessionScoreManager;

    }

    /**
     * 
     * @return type
     */
    public function indexAction()
    {
        $request = $this->getRequest();

        $answerManager = $this->answerManager;
        $this->fromSession();

        if (! $request->isPost()) {
            return $this->getView( $answerManager->getActiveQuestion()->getStatus());
        }

        $status = $answerManager->checkAnswer($request->getPost()->answer, $request->getPost()->donotknow);
        
        if ($status == "correct" || $status == "incorrect") {
              $this->updateScores($answerManager->getLastActiveQuestion());
              $this->sessionScoreManager->updateSessionScore($answerManager->getLastActiveQuestion());
        }
 
        $this->toSession();
        
        return $this->getView($status);
    }

    # +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 
    private function getView($status) {

        $am = $this->getAnswerManager();
        $question = $am->getActiveQuestion();

        $answerToLastQuestion = $am->getLastActiveQuestion() ? $am->getLastActiveQuestion()->getAnswerText() : "";

        $form = new QuestionForm();
        $form->get('submit')->setValue('Submit Answer');

        $form->get('question')->setValue($question->getQuestionText());

        $view = new ViewModel([
            'status' => $status,
            'answerToLastQuestion' => $answerToLastQuestion,
            'score' => $this->getSessionScoreManager()->getSessionScore(),
            'form' => $form
        ]);
        return $view;
    }

    private function updateScores($lastActiveQuestion) {
        $this->scoreManager->setUserId(1)
                ->setActiveQuestion($lastActiveQuestion)
                ->update();
    }

    private function fromSession()
    {
       if ($this->sessionContainer->activeQuestion){
          $this->getAnswerManager()->setActiveQuestion($this->sessionContainer->activeQuestion);
       }

        if ($this->sessionContainer->sessionScore){
          $this->getSessionScoreManager()->setSessionScore($this->sessionContainer->sessionScore);
        }

       return $this;
    }

    private function toSession()
    {
       $this->sessionContainer->activeQuestion = $this->getAnswerManager()->getActiveQuestion();
       $this->sessionContainer->sessionScore = $this->getSessionScoreManager()->getSessionScore();
       return $this;
    }

    private function getAnswerManager() {
        return $this->answerManager;
    }
    
    private function getScoreManager() {
       return $this->scoreManager;
    }
    
    private function getSessionScoreManager() {
        return $this->sessionScoreManager;
    }
}
