<?php

namespace sadi01\mutexFilter;

use Yii;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\web\Controller;

/**
 * MutexFilter is an action filter that apply mutex on action.
 *
 * To use MutexFilter, declare it in the `behaviors()` method of your controller class.
 * For example,
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'mutexFilter' => [
 *             'class' => \sadi01\mutexFilter\MutexFilter::class,
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Sadegh Shafii <sadshafiei.01@gmail.com>
 * @since 2.0
 */
class MutexFilter extends Behavior
{
    /**
     * @var array
     */
    public $actions = [];

    /**
     * @var array
     */
    public $exceptActions = [];

    /**
     * @var string
     */
    public $mutexKeyPostfix;

    /**
     * @var string
     */
    protected $mutex;

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
            Controller::EVENT_AFTER_ACTION => 'afterAction'
        ];
    }

    /**
     * @param ActionEvent $event
     * @return bool
     */
    public function beforeAction($event)
    {
        $this->mutexKeyPostfix = $this->mutexKeyPostfix ?: Yii::$app->user->id;

        if (in_array($event->action->id, $this->actions) || !in_array($event->action->id, $this->exceptActions)) {
            $mutexKey = $event->action->id . '-' . $this->mutexKeyPostfix;
            $this->mutex = Yii::$app->mutex->acquire($mutexKey, 20);
            if (!$this->mutex) {
                $event->isValid = false;
            }
        }

        return $event->isValid;
    }

    public function afterAction($event)
    {
        if ($this->mutex) {
            Yii::$app->mutex->release($this->mutex);
        }

        return $event->isValid;
    }
}