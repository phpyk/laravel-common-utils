<?php
/**
 * Created by PhpStorm.
 * User: kk
 * Date: 16/8/10
 * Time: 下午2:38
 * 官方文档:http://www.maatwebsite.nl/laravel-excel/docs/export
 */
namespace Phpyk\Utils;

use Maatwebsite\Excel\Facades\Excel;

class ExcelMaker
{
    private $_excelName = 'testexcel';
    //是否导出
    private $_toExport = true;
    //是否保存在服务器,保存路径:storage/exprots
    private $_toStore = false;
    //扩展名
    private $_fileExt= 'xls';
    /**
     * 格式:
     *     $_sheetData = [
     *           'sheetName1' => array1,
     *           'sheetName2' => array2
     *           ];
     * @var array
     */
    private $_sheetData = [];


    public function createExcel()
    {
        $excelName = $this->_excelName;
        $sheetDataArr = $this->_sheetData;

        $myExcel = Excel::create($excelName, function($excel) use ($sheetDataArr){
            if(count($sheetDataArr) > 0){
                foreach ($sheetDataArr as $sheetName => $data) {
                    if(!is_array($data)){
                        $data = $data->toArray();
                    }
                    $excel->sheet($sheetName, function($sheet) use ($data){
                        // Won't auto generate heading columns
                        $sheet->fromArray($data, null, 'A1',false, false);
                    });
                }
            }
        });
        if($this->_toExport){
            $myExcel->export($this->_fileExt);
        }
        if($this->_toStore){
            //存储并返回路径
            return $myExcel->store($this->_fileExt,false,true);
        }
    }

    /**
     * @return string
     */
    public function getExcelName()
    {
        return $this->_excelName;
    }

    /**
     * @param string $excelName
     */
    public function setExcelName($excelName)
    {
        $this->_excelName = $excelName;
    }

    /**
     * @param string $fileType
     */
    public function setFileExt($fileType)
    {
        $this->_fileExt = $fileType;
    }

    /**
     * @param array $sheetData
     */
    public function setSheetData($sheetData)
    {
        if(!is_array($sheetData)){
            $sheetData = $sheetData->toArray();
        }
        $this->_sheetData = $sheetData;
    }

    /**
     * @param boolean $toExport
     */
    public function setToExport($toExport)
    {
        $this->_toExport = $toExport;
    }

    /**
     * @param boolean $toStore
     */
    public function setToStore($toStore)
    {
        $this->_toStore = $toStore;
    }

}