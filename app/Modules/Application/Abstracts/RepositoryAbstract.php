<?php


namespace App\Modules\Application\Abstracts;


abstract class RepositoryAbstract
{
    protected $with=[];
    protected $select='*';

    protected $orderByColumn="created_at";
    protected $orderDirection="asc";
    protected $skip=0;
    protected $take=1000000000;

    protected $columnNames =[];

    //protected $getMethod = 'first';
    //->{$this->getMethod}(); //implementation

    public function select(array $selectColumns){
        $this->select = $selectColumns;
        return $this;
    }

    public function with(array $with){
        $this->with = $with;
        return $this;
    }

    public function orderBy($orderByColumn="created_at",$orderDirection="asc"){
        $this->orderByColumn = $orderByColumn;
        $this->orderDirection = $orderDirection;
        return $this;
    }

    public function skip(int $skip){
        $this->skip = $skip;
        return $this;
    }

    public function take(int $take){
        $this->take = $take;
        return $this;
    }

    public function where($columnName,$columnValue,$operator='='){
        $this->columnNames[$columnName] = [
            'value' => $columnValue,
            'operator' => $operator
        ];
        return $this;
    }
}
