<?php

namespace RL\Model;

use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UserQuestionTable
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

    public function getUserQuestion($userId)
    {
        $userId = (int) $userId;
        $rowset = $this->tableGateway->select(['user_id' => $userId]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $userId
            ));
        }

        return $row;
    }

    public function saveUserQuestion(UserQuestion $userQuestion)
    {
        $data = [
            'user_id' => $userQuestion->user_id,
            'question_id' => $userQuestion->question_id,
            'score_qa'  => $userQuestion->score_qa,
            'score_aq'  => $userQuestion->score_aq,
        ];

        $id = (int) $userQuestion->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getUserQuestion($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update question with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteUserQuestion($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

}
