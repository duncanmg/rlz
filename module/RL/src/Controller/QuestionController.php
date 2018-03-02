<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use RL\Model\QuestionTable;
use RL\Model\Question;
use RL\Model\AnswerManager;
use RL\Model\ScoreManager;
use Zend\Session\Container;

use RL\Form\QuestionForm;

class QuestionController extends AbstractActionController
{

    private $table;
    private $answerManager;
    private $sessionContainer;
    private $scoreManager;

    public function __construct(QuestionTable $table, AnswerManager $answerManager, Container $sessionContainer, ScoreManager $scoreManager)
    {
        $this->table = $table;
        $this->answerManager = $answerManager;
        $this->sessionContainer = $sessionContainer;
        $this->scoreManager = $scoreManager;

    }

    public function indexAction()
    {
        $request = $this->getRequest();

        $answerManager = $this->answerManager;
        $this->fromSession($answerManager);

        if (! $request->isPost()) {
            return $this->getView();
        }

        $correct = $answerManager->checkAnswer($request->getPost()->answer, $request->getPost()->donotknow);

        $this->toSession($answerManager);

        if($correct) {
            $this->updateScores($answerManager->getLastActiveQuestion());
            return $this->redirect()->toRoute('question', ['action' => 'index']);
        }
        else if ( $answerManager->getActiveQuestion()->getTries()){
            $message = 'Sorry. Your answer was incorrect. Please try again.';
            return $this->getView($message);
        }

        $this->updateScores($answerManager->getLastActiveQuestion());
        $message = 'The correct answer to the last question was: ' . $answerManager->getLastActiveQuestion()->getAnswerText();
        return $this->getView($message);
    }

    private function updateScores($lastActiveQuestion) {
       $this->scoreManager->setUserId(1)
         ->setActiveQuestion($lastActiveQuestion)
         ->update();   
    }

    private function fromSession($answerManager)
    {
       if ($this->sessionContainer->activeQuestion){
          $answerManager->setActiveQuestion($this->sessionContainer->activeQuestion);
       }
       # $answerManager->setTries($this->sessionContainer->tries);
       # $answerManager->setDirection($this->sessionContainer->direction);
       return $this;
    }

    private function toSession($answerManager)
    {
    
       $this->sessionContainer->activeQuestion = $answerManager->getActiveQuestion();
       # $this->sessionContainer->tries = $answerManager->getTries();
       # $this->sessionContainer->direction = $answerManager->getDirection();
       return $this;
    }

    private function getView($message = '') {

        $question = $this->answerManager->getActiveQuestion();
        $form = new QuestionForm();
        $form->get('submit')->setValue('Submit Answer');

        $form->get('question')->setValue($question->getQuestionText());

        $view =  new ViewModel([
            'message' => $message,
            'form' => $form
        ]);
        return $view;
    }

}
