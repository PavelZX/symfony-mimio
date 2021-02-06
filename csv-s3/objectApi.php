<?php
require_once 'api.php';
require_once 'objects.php';
require_once 's3.php';

class ObjectsApi extends Api
{
    public $s3 = new Aws\S3\S3Client([
        'version' => 'latest',
        'region'  => '',
        'endpoint' => 'http://localhost:9000',
        'use_path_style_endpoint' => true,
        'credentials' => [
                'key'    => 'minio-admin',
                'secret' => 'minio-admin',
            ],
    ]);

    /**
     * Метод GET
     * Вывод списка всех записей
     * http://ДОМЕН/objects
     * @return string
     */
    public function indexAction()
    {
        $objects = Objects::getAll($s3);
        if($objects){
            return $this->response($objects, 200);
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http://ДОМЕН/objects/1
     * @return string
     */
    public function viewAction()
    {
        //id должен быть первым параметром после /objects/x
        $id = array_shift($this->requestUri);

        if($id){
            $object = Objects::getById($s3, $id);
            if($object){
                return $this->response($object, 200);
            }
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Метод POST
     * Создание новой записи
     * http://ДОМЕН/objects + параметры запроса name, email
     * @return string
     */
    public function createAction()
    {
        $name = $this->requestParams['name'] ?? '';
        $email = $this->requestParams['email'] ?? '';
        if($name && $email){
            $object = new Objects($s3, [
                'name' => $name,
                'email' => $email
            ]);
            if($object = $object->saveNew()){
                return $this->response('Data saved.', 200);
            }
        }
        return $this->response("Saving error", 500);
    }

    /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http://ДОМЕН/objects/1 + параметры запроса name, email
     * @return string
     */
    public function updateAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $objectId = $parse_url['path'] ?? null;

        if(!$objectId || !Objects::getById($s3, $objectId)){
            return $this->response("Object with id=$objectId not found", 404);
        }

        $name = $this->requestParams['name'] ?? '';
        $email = $this->requestParams['email'] ?? '';

        if($name && $email){
            if($object = Objects::update($s3, $objectId, $name, $email)){
                return $this->response('Data updated.', 200);
            }
        }
        return $this->response("Update error", 400);
    }

    /**
     * Метод DELETE
     * Удаление отдельной записи (по ее id)
     * http://ДОМЕН/objects/1
     * @return string
     */
    public function deleteAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $objectId = $parse_url['path'] ?? null;

        if(!$objectId || !Objects::getById($s3, $objectId)){
            return $this->response("Object with id=$objectId not found", 404);
        }
        if(Objects::deleteById($s3, $objectId)){
            return $this->response('Data deleted.', 200);
        }
        return $this->response("Delete error", 500);
    }

}
