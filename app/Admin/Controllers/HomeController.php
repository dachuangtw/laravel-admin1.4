<?php

namespace App\Admin\Controllers;

use App\Sales;
use App\SalesNotes;
use App\ProductIndex;
use App\WebLocation;
use App\ProductCategory;
use App\Warehouse;

use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Chart\Bar;
use Encore\Admin\Widgets\Chart\Doughnut;
use Encore\Admin\Widgets\Chart\Line;
use Encore\Admin\Widgets\Chart\Pie;
use Encore\Admin\Widgets\Chart\PolarArea;
use Encore\Admin\Widgets\Chart\Radar;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('經營儀錶板');
            //$content->description('Description...');

            $content->row(function ($row) {
                $row->column(2, new InfoBox('業務人數', 'users', 'aqua', '/admin/sales', Sales::where(['resign' => 0])->count()));
                $row->column(2, new InfoBox('業務訂單', 'shopping-cart', 'green', '/admin/orders', '150%'));
                $row->column(2, new InfoBox('業務公告', 'file', 'red', '/admin/sales/notes', SalesNotes::count()));
                $row->column(2, new InfoBox('今日領貨量', 'check-square', 'purple', '/admin', '0'));
                $row->column(2, new InfoBox('商品樣數', 'cubes', 'orange', '/admin/product', ProductIndex::count()));
                $row->column(2, new InfoBox('店鋪數', 'university', 'blue', '/admin/web/location', WebLocation::count()));

            });
            $content->row(function (Row $row) {

                $row->column(6, function (Column $column) {
                    $tab = new Tab();


                    $pieArray = [];
                    $ProductCategory = ProductCategory::all()->pluck('pc_name', 'pcid');
                    foreach ($ProductCategory as $key => $val) {
                        $pieArray[] = [$val,ProductIndex::ofCategory($key)->count()];
                    }
                    $column->append((new Box('商品種類/樣數', new Pie($pieArray)))->removable()->collapsable()->style('info'));


                });
                $row->column(6, function (Column $column) {
                    $pieArray = [];
                    $Warehouse = Warehouse::orderBy('sort')->pluck('name','id');
                    foreach($Warehouse as $key => $val){
                        $pieArray[] = [$val,Sales::where('warehouse_id',$key)->count()];
                    }
                    $column->append((new Box('業務分布', new Pie($pieArray)))->removable()->collapsable()->style('info'));
                });

            });
            $content->row(function (Row $row) {


                $row->column(6, function (Column $column) {

                    $tab = new Tab();



                    $tab->add('Table', new Table());
                    $tab->add('Text', 'blablablabla....');

                    $tab->dropDown([['Orders', '/admin/orders'], ['administrators', '/admin/administrators']]);
                    $tab->title('Tab');

                    $column->append($tab);

                    $collapse = new Collapse();

                    $bar = new Bar(
                        ["January", "February", "March", "April", "May", "June", "July"],
                        [
                            ['First', [40,56,67,23,10,45,78]],
                            ['Second', [93,23,12,23,75,21,88]],
                            ['Third', [33,82,34,56,87,12,56]],
                            ['Forth', [34,25,67,12,48,91,16]],
                        ]
                    );
                    $collapse->add('Bar', $bar);
                    $collapse->add('Orders', new Table());
                    $column->append($collapse);

                    $doughnut = new Doughnut([
                        ['Chrome', 700],
                        ['IE', 500],
                        ['FireFox', 400],
                        ['Safari', 600],
                        ['Opera', 300],
                        ['Navigator', 100],
                    ]);
                    $column->append((new Box('Doughnut', $doughnut))->removable()->collapsable()->style('info'));
                });

                $row->column(6, function (Column $column) {

                    $column->append(new Box('Radar', new Radar()));

                    $polarArea = new PolarArea([
                        ['Red', 300],
                        ['Blue', 450],
                        ['Green', 700],
                        ['Yellow', 280],
                        ['Black', 425],
                        ['Gray', 1000],
                    ]);
                    $column->append((new Box('Polar Area', $polarArea))->removable()->collapsable());

                    $column->append((new Box('Line', new Line()))->removable()->collapsable()->style('danger'));
                });

            });

            $headers = ['Id', 'Email', 'Name', 'Company', 'Last Login', 'Status'];
            $rows = [
                [1, 'labore21@yahoo.com', 'Ms. Clotilde Gibson', 'Goodwin-Watsica', '1997-08-13 13:59:21', 'open'],
                [2, 'omnis.in@hotmail.com', 'Allie Kuhic', 'Murphy, Koepp and Morar', '1988-07-19 03:19:08', 'blocked'],
                [3, 'quia65@hotmail.com', 'Prof. Drew Heller', 'Kihn LLC', '1978-06-19 11:12:57', 'blocked'],
                [4, 'xet@yahoo.com', 'William Koss', 'Becker-Raynor', '1988-09-07 23:57:45', 'open'],
                [5, 'ipsa.aut@gmail.com', 'Ms. Antonietta Kozey Jr.', 'Braun Ltd', '2013-10-16 10:00:01', 'open'],
            ];

            $content->row((new Box('Table', new Table($headers, $rows)))->style('info')->solid());
        });
    }
}
