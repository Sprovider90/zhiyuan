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
class PositiondatasExport implements FromArray,WithEvents
{
    protected $invoices;
    protected $redrule;

    protected $liename=["timestamp"=>"A","formaldehyde"=>"B","TVOC"=>"C","PM25"=>"D","CO2"=>"E","temperature"=>"F","humidity"=>"G"];

    public function __construct(array $invoices,$redrule)
    {
        $this->invoices = $invoices;
        $this->redrule = $redrule;
    }

    public function array(): array
    {
        return $this->invoices;
    }
    protected function logicRed(){
        $result=[];
        foreach ($this->redrule as $k=>$v){
            if(!empty($v)){
                foreach ($v as $kk=>$vv){
                    $result[]=$this->liename[$vv]."".($k+4);
                }
            }
        }
        return $result;
    }
    /**
     * registerEvents.
     * 事件监听
     * @return array
     */
    public function registerEvents(): array
    {

        $reds=$this->logicRed();
        return [

            // 生成表单元后处理事件
            AfterSheet::class => function (AfterSheet $event)use($reds) {

                $event->sheet->getDelegate()->getColumnDimension("A")->setWidth(20);
                if(!empty($reds)){
                    foreach ($reds as $k=>$v){
                        $event->sheet->getDelegate()->getStyle($v)->applyFromArray([
                            // 设置单元格背景色
                            'font' => [
                                'name' => '宋体',
                                'bold' => false,
                                'italic' => false,
                                'strikethrough' => false,
                                'color' => [
                                    'rgb' => 'FF0000',
                                ],
                            ],
                        ]);
                    }
                }
            }
            ];
    }
}
