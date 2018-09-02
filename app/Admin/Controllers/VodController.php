<?php

namespace App\Admin\Controllers;

use App\Vod;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class VodController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Vod::class, function (Grid $grid) {

//            $grid->id('ID')->sortable();
//
//            $grid->created_at();
//            $grid->updated_at();
            $grid->title();
            $grid->pic_path()->display(function ($pic_path) {
                $sFilePath = "http://file.debug.com/" . $pic_path;
                return "<img style='width: 200px;height: 150px' src='$sFilePath'></img>";
            });;
            $grid->pageurl()->display(function ($pageurl) {
                return  "<a href='$pageurl' target='_blank'>查看详情</a>";
            });;
            $grid->vodurl()->display(function ($vodUrl) {
                return "<a href='$vodUrl' target='_blank'>播放</a>";
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Vod::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
