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

        if (! $request->isPost()) {
            return ['form' => $this->setupForm()];
        }

        $correct = $answerManager->setQuestionId($this->getQuestionId())
            ->setTries($this->getTries())
            ->checkAnswer($request->getPost()->answer);
        # die('after checkAnswer ' . $correct);
        $this->setQuestionId($answerManager->getQuestionId());
        $this->setTries($answerManager->getTries());

        if($correct) {
            return $this->redirect()->toRoute('question', ['action' => 'index']);
        }

        $view =  new ViewModel([
        ]);
        $view->setTemplate('/rl/question/message.phtml');
        return $view;
    }

    private function setupForm() {
            $question = $this->table->getQuestion($this->getQuestionId());
        $form = new QuestionForm();
        $form->get('question')->setValue($question->question);

        $form->get('submit')->setValue('Submit Answer');

        return $form;
    }

    private function getQuestionId() {
        if (!  $this->sessionContainer->questionId) {
           $this->setQuestionId($this->answerManager->getQuestionId());
        }
       return $this->sessionContainer->questionId;
    }

    private function setQuestionId($questionId) {
       $this->sessionContainer->questionId = $questionId;
       return $this;
    }

    private function getTries() {
       return $this->sessionContainer->tries;
    }

    private function setTries($tries) {
       $this->sessionContainer->tries = $tries;
       return $this;
    }

}
