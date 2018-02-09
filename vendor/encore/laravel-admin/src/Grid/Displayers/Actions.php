<?php

namespace Encore\Admin\Grid\Displayers;

use Encore\Admin\Admin;

class Actions extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var array
     */
    protected $prepends = [];

    /**
     * @var bool
     */
    protected $allowInventory = false;

    /**
     * @var bool
     */
    protected $allowView = true;

    /**
     * @var bool
     */
    protected $allowEdit = true;
    
    /**
     * @var bool
     */
    protected $allowDelete = true;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $titleField;

    /**
     * @var string
     */
    protected $titleExtra;

    /**
     * Append a action.
     *
     * @param $action
     *
     * @return $this
     */
    public function append($action)
    {
        array_push($this->appends, $action);

        return $this;
    }

    /**
     * Prepend a action.
     *
     * @param $action
     *
     * @return $this
     */
    public function prepend($action)
    {
        array_unshift($this->prepends, $action);

        return $this;
    }

    /**
     * Disable delete.
     *
     * @return void.
     */
    public function disableDelete()
    {
        $this->allowDelete = false;
    }

    /**
     * Disable edit.
     *
     * @return void.
     */
    public function disableEdit()
    {
        $this->allowEdit = false;
    }

    /**
     * Disable view.
     *
     * @return void.
     */
    public function disableView()
    {
        $this->allowView = false;
    }

    /**
     * Enable view.
     *
     * @return void.
     */
    public function ensableInventory()
    {
        $this->allowInventory = true;
    }
    
    /**
     * Set resource of current resource.
     *
     * @param $resource
     *
     * @return void
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get resource of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource ?: parent::getResource();
    }

    /**
     * Set resource of current resource.
     *
     * @param $resource
     *
     * @return void
     */
    public function setTitleField($titleField)
    {
        $this->titleField = $titleField;
    }
    
    public function setTitleExtra($titleExtra)
    {
        $this->titleExtra = $titleExtra;
    }

    /**
     * {@inheritdoc}
     */
    public function display($callback = null)
    {
        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo($this);
            call_user_func($callback, $this);
        }

        $actions = $this->prepends;

        if ($this->allowView) {
            array_push($actions, $this->viewAction());
        }

        if ($this->allowInventory) {
            array_push($actions, $this->inventoryAction());
        }

        if ($this->allowEdit) {
            array_push($actions, $this->editAction());
        }

        if ($this->allowDelete) {
            array_push($actions, $this->deleteAction());
        }

        $actions = array_merge($actions, $this->appends);

        return implode('', $actions);
    }

    /**
     * Built Inventory action.
     *
     * @return string
     */
    protected function inventoryAction()
    {
        return <<<EOT
&nbsp;<a href="{$this->getResource()}/{$this->getKey()}/details" title="{$this->trans('inventory')}">
    <i class="fa fa-outdent"></i>
</a>
EOT;
    }

    /**
     * Built view action.
     *
     * @return string
     */
    protected function viewAction()
    {
        $title = '';
        if($this->titleField){
            foreach($this->titleField as $val){
                $title = ($title ? $title . ' ' : '') . $this->getTitle($val);
            }
        }
        if($this->titleExtra){
            $title = $this->titleExtra . $title;
        }
        $title = htmlentities($title);
        return <<<EOT
<a src="{$this->getResource()}/{$this->getKey()}/view" class="viewbutton" data-toggle="modal" data-target="#viewmodal" data-title="{$title}" title="{$this->trans('view')}" data-key="{$this->getKey()}">
    <i class="fa fa-eye"></i>
</a>
EOT;
    }

    /**
     * Built edit action.
     *
     * @return string
     */
    protected function editAction()
    {
        return <<<EOT
&nbsp;<a href="{$this->getResource()}/{$this->getKey()}/edit" title="{$this->trans('edit')}">
    <i class="fa fa-edit"></i>
</a>
EOT;
    }

    /**
     * Built delete action.
     *
     * @return string
     */
    protected function deleteAction()
    {
        $confirm = trans('admin::lang.delete_confirm');

        $script = <<<SCRIPT

$('.grid-row-delete').unbind('click').click(function() {
    if(confirm("{$confirm}")) {
        $.ajax({
            method: 'post',
            url: '{$this->getResource()}/' + $(this).data('id'),
            data: {
                _method:'delete',
                _token:LA.token,
            },
            success: function (data) {
                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            }
        });
    }
});

SCRIPT;

        Admin::script($script);

        return <<<EOT
&nbsp;<a href="javascript:void(0);" data-id="{$this->getKey()}" class="grid-row-delete" title="{$this->trans('delete')}">
    <i class="fa fa-trash"></i>
</a>
EOT;
    }
}
