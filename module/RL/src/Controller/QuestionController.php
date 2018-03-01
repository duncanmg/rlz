<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use RL\Model\QuestionTable;
use RL\Model\Question;
use RL\Model\AnswerManager;
use Zend\Session\Container;

use RL\Form\QuestionForm;

class QuestionController extends AbstractActionController
{

    private $table;
    private $answerManager;
    private $sessionContainer;

    public function __construct(QuestionTable $table, AnswerManager $answerManager, Container $sessionContainer)
    {
        $this->table = $table;
        $this->answerManager = $answerManager;
        $this->sessionContainer = $sessionContainer;

        if (! isset($this->sessionContainer->tries)) {
            $this->sessionContainer->tries = 0;
        }
    }

    public function indexAction()
    {
        $request = $this->getRequest();

        $answerManager = $this->answerManager;
        $this->fromSession($answerManager);

        if (! $request->isPost()) {
            return $this->getView();
        }

        $correct = $answerManager->checkAnswer($request->getPost()->answer);

        $this->toSession($answerManager);

        if($correct) {
            return $this->redirect()->toRoute('question', ['action' => 'index']);
        }

        $message = $answerManager->getTries() 
            ? 'Sorry. Your answer was incorrect. Please try again.' 
            : 'The correct answer to the last question was: ' . $answerManager->getAnswerToLastQuestion();
        return $this->getView($message);
    }

    private function fromSession($answerManager)
    {
       $answerManager->setQuestionId($this->sessionContainer->questionId);
       $answerManager->setTries($this->sessionContainer->tries);
       $answerManager->setDirection($this->sessionContainer->direction);
       return $this;
    }

    private function toSession($answerManager)
    {
       $this->sessionContainer->questionId = $answerManager->getQuestionId();
       $this->sessionContainer->tries = $answerManager->getTries();
       $this->sessionContainer->direction = $answerManager->getDirection();
       return $this;
    }

    private function getView($message = '') {

        $question = $this->table->getQuestion($this->answerManager->getQuestionId());
        $form = new QuestionForm();
        $form->get('submit')->setValue('Submit Answer');

        $questionText = ($this->answerManager->getDirection() == 'QA')
           ? $question->question
           : $question->answer;

        $form->get('question')->setValue($questionText);

        $view =  new ViewModel([
            'message' => $message,
            'form' => $form
        ]);
        return $view;
    }

}
