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
            array_push($actions, $this->viewModal());
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
     * Built view modal.
     *
     * @return string
     */
    protected function viewModal()
    {
        $script = <<<SCRIPT

$('.viewbutton').on('click', function() {
    var url = $(this).attr("href");
    $.get(url, function(data) {
        $("#viewmodal").find('.modal-body').html(data);
    });
});
$('#viewmodal').on('show.bs.modal', function() {
    var margin_vertical = parseInt($(this).find('.modal-dialog').css('margin-top')) + parseInt($(this).find('.modal-dialog').css('margin-bottom')) || 0;
    var height_body = (window.innerHeight - margin_vertical - 150) + 'px';
    $(this).find('.modal-body').css('max-height', height_body).css('overflow', 'auto');
});

SCRIPT;

        Admin::script($script);

        $close = trans('admin::lang.close');
        $title = trans('admin::lang.view');
        return <<<EOT
<div class='modal fade' id="viewmodal">
    <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                <h3>{$title}</h3>
            </div>
            <div class='modal-body'>彈出式視窗(網頁載入)</div>
            <div class='modal-footer'>
                <button class='btn btn-default' data-dismiss="modal" aria-hidden="true">{$close}</button>
            </div>
        </div>
    </div>
</div>
EOT;
    }

    /**
     * Built view action.
     *
     * @return string
     */
    protected function viewAction()
    {
        return <<<EOT
<a href="{$this->getResource()}/{$this->getKey()}/view" class="viewbutton" data-toggle="modal" data-target="#viewmodal" title="{$this->trans('view')}">
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
