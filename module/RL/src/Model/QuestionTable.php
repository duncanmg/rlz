<?php

namespace RL\Model;

use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class QuestionTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }

    public function countAll($paginated = false)
    {
        return count($this->tableGateway->select());
    }

    public function getQuestion($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveQuestion(Question $question)
    {
        $data = [
            'question' => $question->question,
            'answer'  => $question->answer,
            'aq_enabled_yn'  => $question->aq_enabled_yn,
            'source_id'      => $question->source_id,
        ];

        $id = (int) $question->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getQuestion($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update question with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteQuestion($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

}
