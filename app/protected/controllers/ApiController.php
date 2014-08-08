<?php

class ApiController extends Controller {

    // Members
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
    }

    // Actions
    public function actionList() {
        // Get the respective model instance
        switch ($_GET['model']) {
            case 'user':
                $models = User::model()->findAll();
                break;
            default:
                // Model not implemented error
                $this->_sendResponse(501, sprintf('Error: Mode <b>list</b> is not implemented for model <b>%s</b>', $_GET['model']));
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
        echo json_encode('list');
    }

    public function actionView() {
        echo json_encode('view');
    }

    public function actionCreate() {
        switch ($_GET['model']) {
            // Get an instance of the respective model
            case 'posts':
                $model = new Post;
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
        echo json_encode('create');
    }

    public function actionUpdate() {
        // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
        $json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
        $put_vars = CJSON::decode($json, true);  //true means use associative array

        switch ($_GET['model']) {
            // Find respective model
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

        echo json_encode('update');
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

}
