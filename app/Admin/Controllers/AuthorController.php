<?php

namespace App\Admin\Controllers;

use App\Author;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class AuthorController extends Controller
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

            $content->header('歌手管理');
            $content->description('全部歌手');

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

            $content->header('歌手管理');
            $content->description('全部歌手');

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

            $content->header('歌手管理');
            $content->description('全部歌手');

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
        return Admin::grid(Author::class, function (Grid $grid) {

//            $grid->id('ID')->sortable();
//
//            $grid->created_at();
//            $grid->updated_at();
            $grid->aname('歌手');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Author::class, function (Form $form) {

            $form->display('aname', '歌手');
        });
    }
}
