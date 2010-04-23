<?php
/**
 * Extending CActiveRecord to add common functionality to all models
 */
class ARBaseExt extends CActiveRecord {
    private $_oldAttributes = array();

    /**
     * $model->behaviors
     *
     * Defines behaviors that Yii should apply to the ActiveRecord class
     *
	 * @todo Place IF statements around each behavior, to ensure needed dependencies (columns) exist before loading
     * @return array
     */
    public function behaviors() {
        return array(
            'CTimestampBehavior'=>array(
                'class'=>'zii.behaviors.CTimestampBehavior',
                'createAttribute'=>'created',
                'updateAttribute'=>'modified',
                'setUpdateOnCreate'=>true,
            ),
            'SystemLogBehavior'=>array(
                'class'=>'application.components.behaviors.SystemLogBehavior',
                'logModel'=>'SystemLog',
                'logChangeModel'=>'SystemLogChange',
            ),
        );
    }

    protected function afterFind() {
        // Save old values
        $this->setOldAttributes($this->getOwner()->getAttributes());

        return parent::afterFind();
    }

    public function getOldAttributes() {
        return $this->_oldAttributes;
    }

    public function setOldAttributes($value) {
        $this->_oldAttributes = $value;
    }

    /**
     * Returns list of elements that should not be editable
     *
     * @return array
     */
    public function lockedElements() {
        return array(
            'created'=>array(
                'hidden'=>false,
            ),
            'modified'=>array(
                'hidden'=>false,
            ),
        );
    }
}