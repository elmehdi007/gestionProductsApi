<?php

namespace App\Repositories;

use App\Interfaces\BaseContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\ResultData;
use App\Helpers\ResponseJson;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class BaseRepository
 *
 * @package \App\Repositories
 */
class BaseRepository {

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var skip
     */
    protected $skip;

    /**
     * @var take
     */
    protected $take;

    /**
     * @var sortBy
     */
    protected $sortBy;

    /**
     * @var sortDir
     */
    protected $sortDir;

    public function getModel(){return $this->model; }
    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model, Request $request) {
      $this->model = $model;
      $this->request = $request;
      $this->skip = $request->get('skip', env("pagination.skip",0));
      $this->take = $request->get('take', env("pagination.take",10));
      $this->sortBy = $request->get('sortBy', env("tri.sortBy","created_at"));
      $this->sortDir = (int)$request->get('sortDir', env("tri.sortDir",1))>=1?"asc":"desc";
      //if($this->sortDir!="desc" && $this->sortDir!="asc") $this->sortDir!="asc";
      if($request->get('search_for_maj')){
        $searchForMaj = $request->get('search_for_maj',[]);
        $this->skip = (isset($searchForMaj["skip"]))?$searchForMaj["skip"]:0;
        $this->take = (isset($searchForMaj["take"]))?$searchForMaj["take"]:10;
        $this->sortBy = (isset($searchForMaj["sortBy"]))? $searchForMaj["sortBy"]:"created_at";
        $this->sortDir =(isset($searchForMaj["sortBy"]) && (int)$searchForMaj["sortBy"]>=1)?"asc":"desc";
      }
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function search($columns = array('*'), $condition = []) {
        $rows = $this->model->where($condition)->orderBy($this->sortBy, $this->sortDir);
        
        if(isset($this->skip)) $rows = $rows->skip($this->skip);
        if(isset($this->take)) $rows = $rows->take($this->take);

        return $rows->select($columns)->get();
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function count(array $condition = []) {
        $rows = $this->model;
        if (count($condition) > 0) {
            $rows = $rows->where($condition);
        }
        return $rows->count();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(string $id,$column = ["*"] ) {
        return $this->model->find($id);
    }

     /**
     * @param int $id
     * @return mixed
     */
    public function exists(string $id ) {
        return $this->model->where($this->model->getKeyName(), $id)->exists();
    }

    /**
     * @param string $column
     * @return mixed
     */
    public function findBy(array $condition) {
        return $this->model->where($condition)->first();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function forceDelete(string $id): bool {
        $state = false;
        if ($obj = $this->find($id)) $state = $obj->forceDelete();
        return $state;
    }
}