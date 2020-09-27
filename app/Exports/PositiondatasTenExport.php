<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-08-10
 * Time: 13:51
 */

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class PositiondatasTenExport implements FromArray,WithEvents,WithDrawings
{
    protected $invoices;
    protected $path;

    protected $liename=["timestamp"=>"A","formaldehyde"=>"B","TVOC"=>"C","PM25"=>"D","CO2"=>"E","temperature"=>"F","humidity"=>"G"];

    public function __construct(array $invoices,$path)
    {
        $this->invoices = $invoices;
        $this->path = $path;
    }

    public function array(): array
    {
        return $this->invoices;
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/2020092723344140955028.png'));
        $drawing->setHeight(390);
        $drawing->setCoordinates('J3');

        return $drawing;
    }
    /**
     * registerEvents.
     * 事件监听
     * @return array
     */
    public function registerEvents(): array
    {

        return [
                // 生成表单元后处理事件
                AfterSheet::class => function (AfterSheet $event) {

                    $event->sheet->getDelegate()->getColumnDimension("A")->setWidth(20);

                }
            ];
    }
}
