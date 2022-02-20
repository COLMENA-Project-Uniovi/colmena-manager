<?php

namespace App\Utility;
use Cake\Event\EventManager;
use Neo\MailManager\Listener\MailsListener;



class MailUtility
{
    public function formatParameters($parameters){
        if(isset($parameters['entities']) && $parameters['entities']){
            foreach($parameters['entities'] as $entity){

                if(isset($entity) && $entity){
                    $table_name = isset($entity['plugin_path']) ? $entity['plugin_path'].$entity['name'] : $entity['name'];
        
                    $entity_table = TableRegistry::getTableLocator()->get($table_name);
        
                    if(isset($entity['conditions']) && $entity['conditions']){
                        foreach($entity['conditions'] as $condition){
   
                            switch($condition['type']){
                                case "find":
                                    $query = $entity_table->find($condition['value']);
                                break;
                                case "get":
                                    $query = $entity_table->get($condition['value']);
                                break;
                                case "where":
                                    if($query){
                                        $query->where($condition['value']);
                                    }else{
        
                                    $query = $entity_table->find($condition['value']);
                                    $query->where($condition['value']);
        
                                    }
                                break;
                                case "select":
                                    if($query){
                                        $query->select($condition['value']);
                                    }else{
        
                                    $query = $entity_table->find($condition['value']);
                                    $query->select($condition['value']);
        
                                    }
                                break;
                            }
                        }
                    }
                    $result = $query->toArray();
                    if($result){
                        $parameters['entities']['results'][$entity['name']] = $result;
                    }
                }
            }
        }
        return $parameters;

    }
    
}
