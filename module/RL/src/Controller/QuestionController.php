<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use RL\Model\QuestionTable;
use RL\Model\Question;

use RL\Form\QuestionForm;

class QuestionController extends AbstractActionController
{

    private $table;

    public function __construct(QuestionTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'questions' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new QuestionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $question = new Question();
        $form->setInputFilter($question->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $question->exchangeArray($form->getData());
        $this->table->saveQuestion($question);
        return $this->redirect()->toRoute('question');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('question', ['action' => 'add']);
        }

        // Retrieve the question with the specified id. Doing so raises
        // an exception if the question is not found, which should result
        // in redirecting to the landing page.
        try {
            $question = $this->table->getQuestion($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('question', ['action' => 'index']);
        }

        $form = new QuestionForm();
        $form->bind($question);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($question->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveQuestion($question);

        // Redirect to question list
        return $this->redirect()->toRoute('question', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('question');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteQuestion($id);
            }

            // Redirect to list of questions
            return $this->redirect()->toRoute('question');
        }

        return [
            'id'    => $id,
            'question' => $this->table->getQuestion($id),
        ];
    }
}
