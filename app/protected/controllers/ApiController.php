<?php

class ApiController extends Controller {
    // Members
    // http://www.yiiframework.com/wiki/175/how-to-create-a-rest-api/
    /*
      View all posts: index.php/api/posts (HTTP method GET)
      View a single posts: index.php/api/posts/123 (also GET )
      Create a new post: index.php/api/posts (POST)
      Update a post: index.php/api/posts/123 (PUT)
      Delete a post: index.php/api/posts/123 (DELETE)
     */

    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    Const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';

    /**
     * @return array action filters
     */
    public function filters() {
        return array();
        /* return array(
          'accessControl', // perform access control for CRUD operations
          'postOnly + delete, postOnly + update', // we only allow deletion via POST request
          ); */
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    /* public function accessRules() {
      return array(
      array('allow', // allow all users to perform actions
      'actions' => array('index'),
      'users' => array('*'),
      ),
      );
      } */

    // Actions
    public function actionList() {

        // Get the respective model instance
        switch ($_GET['model']) {
            case 'user':
                $models = User::model()->findAll();
                break;

            case 'auth':
                // AUTHENTICATION
                // check the usrn and password
                $uname = Yii::app()->getRequest()->getQuery('uname');
                $pwd = Yii::app()->getRequest()->getQuery('pwd');
                // check STATUS, USER ROLE & MONITORLY flag
                $user = User::model()->find('LOWER(username)=?', array(strtolower($uname)));
                if ($user === null) {
                    // Error: Unauthorized
                    $this->_sendResponse(401, 'User is invalid');
                } else {
                    // check useridentity file in components
                    $ph = new PasswordHash(Yii::app()->params['phpass']['iteration_count_log2'], Yii::app()->params['phpass']['portable_hashes']);
                    $result = $ph->CheckPassword($pwd, $user->password);
                    if ($result) {
                        $data = array(
                            'id' => $user->id,
                            'name' => $user->fname . ' ' . $user->lname
                        );
                        // Authorized
                        $this->_sendResponse(200, $data);
                    } else {
                        // Error: Unauthorized
                        $this->_sendResponse(401, 'User Password is invalid');
                    }
                }
                Yii::app()->end();

            case 'tasks':
                // fetch user tasks
                $uId = Yii::app()->getRequest()->getQuery('uid');
                $sDate = Yii::app()->getRequest()->getQuery('sdate');
                $eDate = Yii::app()->getRequest()->getQuery('edate');
                $tDone = Yii::app()->getRequest()->getQuery('tdone');
                $start = Yii::app()->getRequest()->getQuery('start');
                $limit = Yii::app()->getRequest()->getQuery('limit');
                // check if the uid is a valid user
                $user = User::model()->findByPk($uId);
                if ($user === null) {
                    // Error: Unauthorized
                    $this->_sendResponse(401, 'User is invalid');
                } else {
                    // Valid User
                    // fetch all the task

                    $start = ($start > 0) ? $start : 0;
                    if ($tDone == 'true') {
                        $sql = "SELECT t.id, c.name AS campaign, ml.name AS site, ml.geoLat AS lat, ml.geoLng AS lng, COUNT( pp.id ) as photocount "
                                . "FROM Task t "
                                . "LEFT JOIN Campaign c ON c.id = t.campaignid "
                                . "LEFT JOIN MonitorlyListing ml ON ml.id = t.siteid "
                                . "LEFT JOIN PhotoProof pp ON pp.taskid = t.id "
                                . "AND pp.clickedDateTime BETWEEN '$sDate' AND '$eDate' "
                                . "WHERE t.taskDone=1 AND t.status=1 AND t.dueDate BETWEEN '$sDate' AND '$eDate' "
                                . "GROUP BY t.id "
                                . "LIMIT {$start}, {$limit}";
                    } else {
                        $sql = "SELECT t.id, c.name AS campaign, ml.name AS site, ml.geoLat AS lat, ml.geoLng AS lng "
                                . "FROM Task t "
                                . "LEFT JOIN Campaign c ON c.id = t.campaignid "
                                . "LEFT JOIN MonitorlyListing ml ON ml.id = t.siteid "
                                . "WHERE t.taskDone=0 AND t.status=1 AND t.dueDate BETWEEN '$sDate' AND '$eDate' "
                                . "GROUP BY t.id "
                                . "LIMIT {$start}, {$limit}";
                    }

                    $tasks = Yii::app()->db->createCommand($sql)->queryAll();
                    $this->_sendResponse(200, $tasks);
                }
                Yii::app()->end();

            default:
                // Model not implemented error              
                $this->_sendResponse(501, 'Mode <b>list</b> is not implemented for model ' . $_GET['model']);
                Yii::app()->end();
        }

        // Did we get some results?
        if (empty($models)) {
            // No
            $this->_sendResponse(200, sprintf('No items where found for model <b>%s</b>', $_GET['model']));
        } else {
            // Prepare response
            $rows = array();
            foreach ($models as $model)
                $rows[] = $model->attributes;
            // Send the response
            $this->_sendResponse(200, CJSON::encode($rows));
        }
    }

    public function actionView() {
        // Check if id was submitted via GET
        if (!isset($_GET['id']))
            $this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');

        switch ($_GET['model']) {
            // Find respective model    
            case 'user':
                $model = User::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501, sprintf('Mode <b>view</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if (is_null($model))
            $this->_sendResponse(404, 'No Item found with id ' . $_GET['id']);
        else
            $this->_sendResponse(200, CJSON::encode($model));
    }

    public function actionCreate() {
        switch ($_GET['model']) {
            case 'task':
            case 'task':
                $this->_sendResponse(200, array("success" => true));
                Yii::app()->end();
                break;
            default:
                $this->_sendResponse(501, sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
        // Try to assign POST values to attributes
        foreach ($_POST as $var => $value) {
            // Does the model have this attribute? If not raise an error
            if ($model->hasAttribute($var))
                $model->$var = $value;
            else
                $this->_sendResponse(500, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']));
        }
        // Try to save the model
        if ($model->save())
            $this->_sendResponse(200, CJSON::encode($model));
        else {
            // Errors occurred
            $msg = "<h1>Error</h1>";
            $msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
            $msg .= "<ul>";
            foreach ($model->errors as $attribute => $attr_errors) {
                $msg .= "<li>Attribute: $attribute</li>";
                $msg .= "<ul>";
                foreach ($attr_errors as $attr_error)
                    $msg .= "<li>$attr_error</li>";
                $msg .= "</ul>";
            }
            $msg .= "</ul>";
            $this->_sendResponse(500, $msg);
        }
    }

    public function actionUpdate() {
        // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
        $json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
        $put_vars = CJSON::decode($json, true);  //true means use associative array

        switch ($_GET['model']) {
            // Find respective model
            // tid, tdone, problemFlag, lat, lng, timestamp, clickedby, photoname, problems:{installation, lighting, obstruction, comments}
            // CREATE TASK
            case 'task':
                $taskId = $_GET['id'];
                $currDateTime = date("Y-m-d H:i:s");
               
               
                $imageData = base64_decode($put_vars['photo']);
                $imageName = trim($put_vars['photoname']);                
                $source = imagecreatefromstring($imageData);
                
                
                $uploadedFile = imagejpeg($source, "uploads/listing/".$imageName, 100);

                // send them to aws s3
                $s3Obj = new EatadsS3();
                $ext = pathinfo($imageName, PATHINFO_EXTENSION);
                $newFileName = 'mon_'.$taskId.'_'.time() . '_' . mt_rand() . '.' . $ext;
                $uploadFilePath = Yii::app()->params['fileUploadPath'] . 'listing/';
                $originalFileWithPath = $uploadFilePath . $imageName;                                
                
                $imageThumb = new EasyImage($originalFileWithPath);
                $newFileThumbName = $uploadFilePath . $newFileName;

                copy($originalFileWithPath, $newFileThumbName);
                $s3Obj->uploadFile($newFileThumbName, 'listing/' . $newFileName);
                @unlink($newFileThumbName);                                          
                
                $imageThumb->resize(487, 310);
                $newFileThumbName = $uploadFilePath . 'big_' . $newFileName;
                $imageThumb->save($newFileThumbName);
                $s3Obj->uploadFile($newFileThumbName, 'listing/big_' . $newFileName);
                @unlink($newFileThumbName);

                $imageThumb->resize(212, 160);
                $newFileThumbName = $uploadFilePath . 'small_' . $newFileName;
                $imageThumb->save($newFileThumbName);
                $s3Obj->uploadFile($newFileThumbName, 'listing/small_' . $newFileName);
                @unlink($newFileThumbName);

                $imageThumb->resize(102, 74);
                $newFileThumbName = $uploadFilePath . 'tiny_' . $newFileName;
                $imageThumb->save($newFileThumbName);
                $s3Obj->uploadFile($newFileThumbName, 'listing/tiny_' . $newFileName);
                @unlink($newFileThumbName);
                @unlink($originalFileWithPath);
               
                $installationProblem = ($put_vars['problems']['installation'] == '') ? NULL : trim($put_vars['problems']['installation']);
                $lightingProblem = ($put_vars['problems']['lighting'] == '') ? NULL : trim($put_vars['problems']['lighting']);
                $obstructionProblem = ($put_vars['problems']['obstruction'] == '') ? NULL : trim($put_vars['problems']['obstruction']);
                $commentProblem = ($put_vars['problems']['comments'] == '') ? NULL : trim($put_vars['problems']['comments']);

                // PHOTOPROOF
                $ppModel = new PhotoProof();
                $ppModel->taskid = $taskId;
                $ppModel->imageName = $newFileName;
                $ppModel->clickedDateTime = $put_vars['timestamp'];
                $ppModel->clickedBy = $put_vars['clickedby'];
                $ppModel->clickedLat = $put_vars['lat'];
                $ppModel->clickedLng = $put_vars['lng'];
                $ppModel->installation = $installationProblem;
                $ppModel->lighting = $lightingProblem;
                $ppModel->obstruction = $obstructionProblem;
                $ppModel->comments = $commentProblem;
                $ppModel->createdDate = $currDateTime;
                $ppModel->modifiedDate = $currDateTime;
                $ppModel->save();
                
                // TASK
                // if any problem then problemFlag will be true
                // if photoclicked then taskDone will be true
                $taskDoneFlag = 0;
                $problemFlag = 0;
                if ($ppModel->getPrimaryKey()) {
                    $taskDoneFlag = 1;
                }
                if (!is_null($installationProblem) || !is_null($lightingProblem) || !is_null($obstructionProblem) || !is_null($commentProblem)) {
                    $problemFlag = 1;
                }
                $taskModel = Task::model()->findByPk($taskId);
                $taskModel->taskDone = $taskDoneFlag;
                $taskModel->problem = $problemFlag;
                $taskModel->modifiedDate = $currDateTime;
                
                if ($taskModel->save()) {
                    
                    $this->_sendResponse(200, array("success" => true));
                } else {
                    $this->_sendResponse(200, array("success" => false));
                }
                Yii::app()->end();
                break;
            case 'posts':
                $model = Post::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501, sprintf('Error: Mode <b>update</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
        // Did we find the requested model? If not, raise an error
        if ($model === null)
            $this->_sendResponse(400, sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.", $_GET['model'], $_GET['id']));

        // Try to assign PUT parameters to attributes
        foreach ($put_vars as $var => $value) {
            // Does model have this attribute? If not, raise an error
            if ($model->hasAttribute($var))
                $model->$var = $value;
            else {
                $this->_sendResponse(500, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']));
            }
        }
        // Try to save the model
        if ($model->save())
            $this->_sendResponse(200, CJSON::encode($model));
        else
        // prepare the error $msg
        // see actionCreate
        // ...
            $this->_sendResponse(500, $msg);
    }

    public function actionDelete() {
        switch ($_GET['model']) {
            // Load the respective model
            case 'posts':
                $model = User::model()->findByPk($_GET['id']);
                break;
            default:
                $this->_sendResponse(501, sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>', $_GET['model']));
                Yii::app()->end();
        }
        // Was a model found? If not, raise an error
        if ($model === null)
            $this->_sendResponse(400, sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.", $_GET['model'], $_GET['id']));

        // Delete the model
        $num = $model->delete();
        if ($num > 0)
            $this->_sendResponse(200, $num);    //this is the only way to work with backbone
        else
            $this->_sendResponse(500, sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.", $_GET['model'], $_GET['id']));
    }

    private function _sendResponse($status = 200, $body = '', $content_type = 'application/json') {

        // pages with body are easy
        if ($body != '') {
            // send the body
            if ($status == 200) {
                $response = array(
                    'status' => $status,
                    'error' => null,
                    'data' => $body
                );
            } else {
                $response = array(
                    'status' => $status,
                    'error' => $body,
                    'data' => null
                );
            }
            $status = 200;  // so that APP can read them as successful response
            // set the status
            $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
            header($status_header);
            // and the content type
            header('Content-type: ' . $content_type);
            echo CJSON::encode($response);
        }
        // we need to create the body if none is passed
        else {
            // set the status
            $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
            header($status_header);
            // and the content type
            header('Content-type: ' . $content_type);

            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on 
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                    <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                        <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
                    </head>
                    <body>
                        <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
                        <p>' . $message . '</p>
                        <hr />
                        <address>' . $signature . '</address>
                    </body>
                    </html>';
            echo $body;
        }
        Yii::app()->end();
    }

    private function _getStatusCodeMessage($status) {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

}
