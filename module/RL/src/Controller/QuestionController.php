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

        if (! $request->isPost()) {
            return ['form' => $this->setupForm()];
        }

        if ($this->answerManager->check($this->getQuestion(), $request->getPost()->answer)) {
            $this->resetTries();
            $this->resetQuestion();
            return $this->redirect()->toRoute('question', ['action' => 'index']);
        }

        $this->incrementTries();
        if (!$this->checkTriesOk()) {
            $this->resetTries();
            $this->resetQuestion();
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

    private function getQuestion() {
            # error_log('getQuestion');
            return $this->table->getQuestion($this->getQuestionId());
    }

    private function getQuestionId() {
       if (!$this->sessionContainer->questionId) {
            $id = rand(1,5);
            $this->sessionContainer->questionId = $id;
       }
       # error_log('getQuestionId ' . $this->sessionContainer->questionId);
       return $this->sessionContainer->questionId;
    }

    private function resetQuestion() {
       $this->sessionContainer->questionId = 0;
    }

    private function incrementTries() {
      $this->sessionContainer->tries = $this->sessionContainer->tries + 1;
      return $this->sessionContainer->tries;
    }

    private function checkTriesOk() {
      # error_log('checkTriesOk ' . $this->sessionContainer->tries);
      return ($this->sessionContainer->tries < 3) ? true : false;
    }

    private function resetTries() {
      $this->sessionContainer->tries  = 0;
    }

#    public function editAction()
#    {
#        $id = (int) $this->params()->fromRoute('id', 0);
#
#        if (0 === $id) {
#            return $this->redirect()->toRoute('question', ['action' => 'add']);
#        }
#
#        // Retrieve the question with the specified id. Doing so raises
#        // an exception if the question is not found, which should result
#        // in redirecting to the landing page.
#        try {
#            $question = $this->table->getQuestion($id);
#        } catch (\Exception $e) {
#            return $this->redirect()->toRoute('question', ['action' => 'index']);
#        }
#
#        $form = new QuestionForm();
#        $form->bind($question);
#        $form->get('submit')->setAttribute('value', 'Edit');
#
#        $request = $this->getRequest();
#        $viewData = ['id' => $id, 'form' => $form];
#
#        if (! $request->isPost()) {
#            return $viewData;
#        }
#
#        $form->setInputFilter($question->getInputFilter());
#        $form->setData($request->getPost());
#
#        if (! $form->isValid()) {
#            return $viewData;
#        }
#
#        $this->table->saveQuestion($question);
#
#        // Redirect to question list
#        return $this->redirect()->toRoute('question', ['action' => 'index']);
#    }
#
#    public function deleteAction()
#    {
#        $id = (int) $this->params()->fromRoute('id', 0);
#        if (!$id) {
#            return $this->redirect()->toRoute('question');
#        }
#
#        $request = $this->getRequest();
#        if ($request->isPost()) {
#            $del = $request->getPost('del', 'No');
#
#            if ($del == 'Yes') {
#                $id = (int) $request->getPost('id');
#                $this->table->deleteQuestion($id);
#            }
#
#            // Redirect to list of questions
#            return $this->redirect()->toRoute('question');
#        }
#
#        return [
#            'id'    => $id,
#            'question' => $this->table->getQuestion($id),
#        ];
#    }
}
