<?php

namespace RL\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use RL\Model\QuestionTable;
use RL\Model\Question;

use RL\Form\QuestionAdminForm;

/**
 * 
 */
class QuestionAdminController extends AbstractActionController
{

    use CharMapTrait;
    
    private $table;

    public function __construct(QuestionTable $table)
    {
        $this->table = $table;
    }

    /**
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel([
            'questions' => $this->table->fetchAll(),
        ]);
    }

    /**
     * 
     * @return type
     */
    public function addAction() {
        $form = new QuestionAdminForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        $viewModel = new ViewModel(['form' => $form]);
        $view = $this->addCharMap($viewModel);

        if (!$request->isPost()) {
            return $view;
        }

        $question = new Question();
        $form->setInputFilter($question->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $view;
        }

        $question->exchangeArray($form->getData());
        $this->table->saveQuestion($question);
        return $this->redirect()->toRoute('admin');
    }

    /**
     * 
     * @return QuestionAdminForm
     */
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin', ['action' => 'add']);
        }

        // Retrieve the question with the specified id. Doing so raises
        // an exception if the question is not found, which should result
        // in redirecting to the landing page.
        try {
            $question = $this->table->getQuestion($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin', ['action' => 'index']);
        }

        $form = new QuestionAdminForm();
        $form->bind($question);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        $viewModel = new ViewModel($viewData);
        $view = $this->addCharMap($viewModel);

        if (!$request->isPost()) {
            return $view;
        }

        $form->setInputFilter($question->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $view;
        }

        $this->table->saveQuestion($question);

        // Redirect to question list
        return $this->redirect()->toRoute('admin', ['action' => 'index']);
    }

    /**
     * 
     * @return type
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteQuestion($id);
            }

            // Redirect to list of questions
            return $this->redirect()->toRoute('admin');
        }

        return [
            'id'    => $id,
            'question' => $this->table->getQuestion($id),
        ];
    }
}
