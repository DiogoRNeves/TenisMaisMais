<?php
/**
 * Created by PhpStorm.
 * User: diogoneves
 * Date: 01/02/15
 * Time: 15:50
 */

Yii::import('application.vendors.PHPExcel',true);

class CExcelTournamentReader  {
    protected $_worksheet, $_maxRow, $_maxColumn, $_firstDataRow, $_columnNames = array();
    public $currentRow;
    const REGEX_MAIN_DRAW_DATE = '/(\d{1,2})\/(\d{1,2}) a (\d{1,2})\/(\d{1,2})\/(\d{4})/';
    const REGEX_QUALI_MULTIDAYS_DATE = '/(\d{1,2}) a (\d{1,2})\/(\d{1,2})/';
    const REGEX_QUALI_SINGLEDAY_DATE = '/(\d{1,2})\/(\d{1,2})/';
    const REGEX_AGE_BAND_AND_VARIATIONS = '/(?:((?:[^\(])+) (?:\((.*?)\))(?: ?))/'; // should return 1."+35" 2."SF; SM; PF; PM"

    /**
     * @param $filePath
     * @throws Exception
     */
    public function __construct($filePath) {
        $objReader = new PHPExcel_Reader_Excel2007;
        $objPHPExcel = $objReader->load($filePath);
            $sheetName = 'FederationTournament';
            $this->_worksheet = $objPHPExcel->getSheetByName($sheetName);
            if ($this->_worksheet === null) {
                throw new CException("Sheet '$sheetName' not found!'");
            }
            $this->assignColumnNames();
            $this->_maxRow = $this->_worksheet->getHighestRow();
    }

    /**
     * @param bool $index
     * @return int
     * @throws Exception
     */
    public function getMaxColumn($index = true) {
        return $index ? PHPExcel_Cell::columnIndexFromString($this->_maxColumn) : $this->_maxColumn;
    }

    /**
     * @return int
     */
    public function getMaxRow() {
        return $this->_maxRow;
    }

    /**
     * @return mixed
     */
    public function getFirstDataRow()
    {
        return $this->_firstDataRow;
    }

    /**
     * @throws Exception
     */
    private function assignColumnNames()
    {
        if ($this->_worksheet === null) {
            throw new CException("Tournaments worksheet not found!");
        }
        $headerRow = 1;
        $column = 0;
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($this->_worksheet->getHighestColumn());
        while ($this->_maxColumn <= $highestColumnIndex && !CHelper::isNullOrEmptyString($this->getValue($column, $headerRow))) {
            $this->_columnNames[$this->getValue($column, $headerRow)] = $column++;
        }
        $this->_maxColumn = PHPExcel_Cell::stringFromColumnIndex(--$column);
        $this->_firstDataRow = $headerRow + 1;
    }

    /**
     * @param $column
     * @param $row
     * @return mixed
     */
    private function getValue($column, $row = false)
    {
        $row = $row === false ? $this->currentRow : $row;
        return CHelper::removeCarriageReturns($this->_worksheet->getCellByColumnAndRow($column, $row)->getValue());
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getNumber($row = false) {
        return $this->getColumnValue('Num', $row);
    }

    /**
     * @param $attribute
     * @param $row
     * @return mixed
     */
    private function getColumnValue($attribute, $row = false)
    {
        return trim($this->getValue($this->_columnNames[$attribute], $row));
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getName($row = false)    {
        return $this->getColumnValue('Nome', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getLevel($row = false)    {
        $level = $this->getColumnValue('Nivel', $row);
        return CHelper::isNullOrEmptyString($level) ? "EQ" : $level;
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getQualyDate($row = false)    {
        return $this->getColumnValue('Data Quali', $row);
    }
    //daqui
    /**
     * @param $row
     * @return mixed
     */
    public function getMainDrawDate($row = false)    {
        return $this->getColumnValue('Data QP', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getLocal($row = false)    {
        return $this->getColumnValue('Local', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getSurface($row = false)    {
        return $this->getColumnValue('Piso', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getAccomodation($row = false)    {
        return $this->getColumnValue('Aloj', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getMeals($row = false)    {
        return $this->getColumnValue('Alim', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getPrizeMoney($row = false)    {
        return $this->getColumnValue('PM', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getClub($row = false)    {
        $clubName = $this->getColumnValue('Clube/Org', $row);
        $slashPos = strpos($clubName,"/");
        if (substr($clubName, 0, 2) == 'AT' && $slashPos != 0) {
            $clubName = trim(substr($clubName, $slashPos + 1));
        }
        return $clubName;
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getPhoneNumber($row = false)    {
        return $this->getColumnValue('Telefone', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getFaxNumber($row = false)    {
        return $this->getColumnValue('Fax', $row);
    }

    /**
     * @param $row
     * @return mixed
     */
    public function getTournamentTypes($row = false)    {
        return $this->getColumnValue('Esc / Mod', $row);
    }

    public function getMainDrawStartDate()
    {
        $dates = $this->getMainDrawDateDetail();
        return $this->compileStartDate($dates);
    }

    public function getMainDrawEndDate()
    {
        $dates = $this->getMainDrawDateDetail();
        return $this->compileEndDate($dates);
    }

    /**
     * @return array
     */
    private function getMainDrawDateDetail()
    {
        $date = $this->getColumnValue('Data QP');
        $matches = array();
        preg_match(self::REGEX_MAIN_DRAW_DATE, $date, $matches);
        return $this->checkStartAndEndDates(array(
            'startDay' => $matches[1],
            'startMonth' => $matches[2],
            'endDay' => $matches[3],
            'endMonth' => $matches[4],
            'startYear' => $matches[5],
            'endYear' => $matches[5],
        ));
    }

    public function getQualiStartDate()
    {
        $dates = $this->getQualiDateDetail();
        return $this->compileStartDate($dates);
    }

    public function getQualiEndDate()
    {
        $dates = $this->getQualiDateDetail();
        return $this->compileEndDate($dates);
    }

    /**
     * @return array
     */
    private function getQualiDateDetail()
    {
        $date = $this->getColumnValue('Data Quali');
        if (CHelper::isNullOrEmptyString($date)) {
            return null;
        }
        $singleDayMatch = array();
        preg_match(self::REGEX_QUALI_SINGLEDAY_DATE, $date, $singleDayMatch);
        $multiDayMatch = array();
        preg_match(self::REGEX_QUALI_MULTIDAYS_DATE, $date, $multiDayMatch);
        $mainDrawYear = $this->getMainDrawDateDetail()['startYear'];
        $isMultiDayQuali = count($multiDayMatch) > 0;
        return $this->checkStartAndEndDates(array(
            'startDay' => $isMultiDayQuali ? $multiDayMatch[1] : $singleDayMatch[1],
            'startMonth' => $singleDayMatch[2],
            'startYear' => $mainDrawYear,
            'endDay' => $singleDayMatch[1],
            'endMonth' => $singleDayMatch[2],
            'endYear' => $mainDrawYear,
        ));
    }

    private function compileStartDate($dates)
    {
        return $dates == null ? '' : $dates['startYear'] . '-' . $dates['startMonth'] . '-' . $dates['startDay'];
    }

    private function compileEndDate($dates)
    {
        return $dates == null ? '' : $dates['endYear'] . '-' . $dates['endMonth'] . '-' . $dates['endDay'];
    }

    /**
     * @param $result
     * @return mixed
     */
    private function checkStartAndEndDates($result)
    {
        $startDate = new DateTime;
        $startDate->setDate($result['startYear'], $result['startMonth'], $result['startDay']);
        $endDate = new DateTime;
        $endDate->setDate($result['endYear'], $result['endMonth'], $result['endDay']);

        $tempStartDate = clone $startDate;

        if ($endDate->getTimestamp() < $startDate->getTimestamp()) {
            $tempStartDate->sub(new DateInterval('P1M'));
            if ($endDate->getTimestamp() < $tempStartDate->getTimestamp()) {
                $startDate->sub(new DateInterval('P1Y'));
            } else {
                $startDate = clone $tempStartDate;
            }
            $result['startDay'] = $startDate->format('j');
            $result['startMonth'] = $startDate->format('n');
            $result['startYear'] = $startDate->format('Y');
            return $result;
        }
        return $result;
    }

    /**
     * @return array The variations indexed by age band. For instance {'SUB 12' => 'SM; SF', 'SUB 16' => 'SM; SF; PM; PF'}
     */
    public function getTournamentTypesArray()
    {
        $matches = array();
        $numberOfMatches = preg_match_all(self::REGEX_AGE_BAND_AND_VARIATIONS,$this->getTournamentTypes(),$matches);
        $result = array();
        for ($i = 0; $i < $numberOfMatches; $i++) {
            $result[$matches[1][$i]] = $matches[2][$i];
        }
        return $result;
    }
}