<?php

namespace App\Admin\Controllers;

use App\Tab;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class TabController extends Controller
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

            $content->header('曲谱管理');
            $content->description('所有曲谱');

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

            $content->header('曲谱管理');
            $content->description('所有曲谱');

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

            $content->header('曲谱管理');
            $content->description('所有曲谱');

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
        return Admin::grid(Tab::class, function (Grid $grid) {
            $grid->author('歌手')->display(function() {
                $objSong = \App\Song::where('id','=',$this->song_id)->firstOrFail();
                $objAuthor = \App\Author::where('id','=',$objSong->author_id)->firstOrFail();
                return $objAuthor->aname;
            })->sortable();
            $grid->song_id('歌曲名')->display(function($song_id) {
                return \App\Song::find($song_id)->sname;
            })->sortable();
            $grid->content('内容')->display(function ($content) {
                $sContent = $content;
                switch($this->ttype)
                {
                    case 1:
                        $sContent = "<pre style='width: 600px;height: 150px' >$content</pre>";
                        break;
                    case 2:
                        $sContent =  "<img style='width: 200px;height: 150px' src='$content'></img>";
                        break;
                    case 3:
                        $sContent =  "<a href='$content' target='_blank'>GTP下载</a>";
                        break;
                }
                return $sContent;
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
        return Admin::form(Tab::class, function (Form $form) {
            $form->display('content', 'content');
        });
    }
}
