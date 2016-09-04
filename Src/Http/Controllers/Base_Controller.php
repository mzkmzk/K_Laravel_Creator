<?php

namespace K_Laravel_Creator\Http\Controllers;


use Illuminate\Http\Request;

class Base_Controller extends Controller{

    protected $request;

    protected $entity;

    protected  $model = null;


    public function __construct(Request $request){
        $this->request = $request;
        preg_match('/(\w+)_Controller/',get_called_class(),$match);
        $entity_string = "\\App\\Entities\\" .$match[1] . "_Entity";
        $this->entity = new $entity_string;
    }

    public function query(){

        $model = $this->model->query();

        foreach($this->request->all() as $key => $value){
            if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                $model = $model->where($key,$value);
            }
        }

        $model = $model->orderBy("updated_at","desc")
            ->Paginate()
            ->toJson();

       return $model;
    }


    public function insert(){

        foreach($this->request->all() as $key => $value){
            if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                $this->model[$key] = $value;
            }
        }
        $result["result"] = $this->model->save();

        return $this->model;
    }

    public function update(){
        $this->model = $this->model->find($this->request->get('id'));
        foreach($this->request->all() as $key => $value){
            if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                $this->model[$key] = $value;
            }
        }
        $result["result"] = $this->model->save();
        return $result;
    }

    public function delete(){
        $result["result"] = $this->model
            ->where("id",$this->request->get("id"))
            ->delete();
        return $result;
    }

    public function restore(Request $request){
        $result["result"] = $this->model->withTrashed()
            ->where("id",$this->request->get("id"))
            ->restore();
        return $result;
    }



}
