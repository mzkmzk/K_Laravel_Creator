<?php

namespace K_Laravel_Creator\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Base_Controller extends BaseController{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;

    protected $entity;

    protected  $model = null;

    public $superior =null;

    protected $guarded = [];


    public function __construct(Request $request){
        $this->request = $request;
        preg_match('/(\w+)_Controller/',get_called_class(),$match);
        $entity_string = "\\App\\Entities\\" .$match[1] . "_Entity";
        //以后解决,因为Entites应该是可以根据调用的Controller所确定的
        if (!class_exists($entity_string)) { 
            $entity_string = "\\App\\Entities\\Creator_" .$match[1] . "_Entity";
        }
       
        $this->entity = new $entity_string;
    }

    public function call_query(){
        $model = $this->model->query();
        $where_array = json_decode( $this->request->get('where')   );
        
        foreach($this->request->all() as $key => $value){
            if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                $model = $model->where($key,$value);
            }
        }

        if ( gettype($where_array) === 'array' )
        foreach ($where_array as $key => $value) {
            if (array_key_exists($value->key,$this->entity->get_attribute()) ==true){
                $model = $model->where($value->key, $value->condition, $value->value);
            }
        }

        $model = $model->orderBy("updated_at","desc")
            ->Paginate()
            ->toJson();

       return $model;
    }
    /*public function call_query(){
        
        $model = $this->model->query();

        foreach($this->request->all() as $key => $value){
            if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                $model = $model->where($key,$value);
            }
        }
        //dump($this->model);
        // dump($model);
         //dump($this->model->creator_media);
        $model = $model
            //->join('Creator_Media','Creator_Activity.id','=','Creator_Media.creator_activity_id')
            ->orderBy("updated_at","desc");
        //dump($model);
        $model = $model
            ->Paginate()
            ->toJson();

       return $model;
    }*/

    //待优化.性能和返回
    public function call_insert($entity_array = []){
         //批量 外部接口 
        $entities = json_decode($this->request->get('k_creator_entities'));

        /*if(is_array($entities) ) {
             $result["result"] = false;
            foreach ($entities as $entity) {
               foreach($entity as $key => $value){
                    if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                        $this->model[$key] = $value;
                    }
                }
                 $result["result"] = $this->model->save();
            }
            return $result;
        }*/
        if(is_array($entities) ) {
            $result["result"] = false;
            foreach ($entities as $entity) {
                $create_entity = [];
               foreach($entity as $key => $value){
                    if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                        //$this->model[$key] = $value;
                        $create_entity[$key] = $value;
                    }
                }
                //dump($create_entity);
                $result["result"] = $this->model->create($create_entity);
                 //$result["result"] = $this->model->save();
            }
            return $result;
        }


        //dump('call_insert');
        $request_all = [];
        
        if ( !is_array($entity_array)) {
            $request_all = $this->request->all();
        }else {
            $request_all = $entity_array;
        }
        //dump($request_all);
        $length = $this->getObjectAttributeLength($request_all);
        $result_data = [];
        $result = [];
        if ($length === 0 ) {
            foreach($request_all as $key => $value){
                if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                    $this->model[$key] = $value;
                }
            }
            $result["result"] = $this->model->save();
            $result['data'] = [$this->model];
        }else {
            for($index =0;$index<$length;$index++){
                $attribute = [];
                foreach($request_all as $key => $value){
                    if (array_key_exists($key,$this->entity->get_attribute()) ==true){
                         $attribute[$key] = $value[$index];
                    }
                }
                //dump($this->model);
                //dump($attribute);
                $create_model = $this->model->create($attribute);
                array_push($result_data,$create_model);
                $result["result"] = !is_null($create_model);
            }
            $result['data'] = $result_data;
        }
        
        
        return $result;
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

    public function __call($methodName, $args) {
        $callMethodName = "call_".$methodName;
        //dump($callMethodName);
        //dump(method_exists($this, $callMethodName));
        if(method_exists($this, $callMethodName)) {
            return call_user_func_array(array($this, $callMethodName), $args);
        }
    }

    private function getObjectAttributeLength($check_object){
       // dump($check_object);
        $array_length = 0;
        foreach($check_object as $key=>$value){
            if($array_length == 0 && count($value) !=0){
                $array_length = count($value);
            }
            if((!is_array($value)) || count($value)!=$array_length){
                return 0;
            }
        }
        //dump($array_length);
        return $array_length;
    }

    protected function createAttributeArray($key, $value, $length) {
        $result_array = [];
       // dump($length);
        for($length-- ; $length >=0; $length--) {
            array_push($result_array, $value);
        }
        return [
            $key => $result_array
        ];
    }



    protected function removeAttribute(&$array,$attribute) {
        $attribute_array = [
            $attribute => $array[$attribute]
        ];
        unset($array[$attribute]);
        return $attribute_array;
    }





}
