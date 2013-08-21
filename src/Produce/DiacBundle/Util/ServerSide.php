<?php

namespace Produce\DiacBundle\Util;

/**
 * Description of ServerSide
 *
 * @author Alex Santiago
 */
class ServerSide {

    private $sIndexColumn;
    private $sTable;
    private $Where;

    private $aColumns = array();
    private $aColumnsName = array();
    private $aColumnsSearch = array();

    public function setIndexColumn($val) {
        $this->sIndexColumn = $val;
    }

    public function getIndexColumn() {
        return $this->sIndexColumn;
    }

    public function setTable($val) {
        $this->sTable = $val;
    }

    public function getTable() {
        return $this->sTable;
    }

    public function setColumns($val) {
        $this->aColumns = $val;
    }

    public function getColumns() {
        return $this->aColumns;
    }

    public function setColumnsName($val) {
        $this->aColumnsName = $val;
    }

    public function getColumnsName() {
        return $this->aColumnsName;
    }

    public function setColumnsSearch($val) {

        $this->aColumnsSearch = $val;
    }

    public function getColumnsSearch() {
        return $this->aColumnsSearch;
    }

    public function setWhere($val) {
        $this->Where = $val;
    }

    public function getWhere() {
        return $this->Where;
    }

    public function data($get,$cn) {

        /* Ordering */
        $sOrder = "";
        if (isset( $get['iSortCol_0'])) {
            $sOrder .= "ORDER BY  ";
            for ($i = 0; $i < intval($get['iSortingCols']); $i++) {
                if ($get[ 'bSortable_'.intval($get['iSortCol_'.$i])] == "true") {
                    $sOrder .= $this->aColumns[intval($get['iSortCol_'.$i])] . "
                                            " . addslashes($get['sSortDir_'.$i]) . ", ";
                }
            }
            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        /* Filtering */
        $sWhere = "";
        if (isset($get['sSearch']) && $get['sSearch'] != '') {
            $sWhere .= "WHERE (";
            for ($i = 0; $i < count($this->aColumnsSearch); $i++) {
                $sWhere .= $this->aColumnsSearch[$i] . " LIKE '%" . addslashes($get['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($this->aColumnsSearch); $i++) {
            if (isset($get['bSearchable_'.$i]) && $get['bSearchable_'.$i] == "true" && $get['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $this->aColumnsSearch[$i] . " LIKE '%" . addslashes($get['sSearch_' . $i]) . "%' ";
            }
        }

        if ($this->Where != '') {

            if ($sWhere == "") {
                $sWhere = "WHERE $this->Where ";
            } else {
                $sWhere .= " AND $this->Where ";
            }
        }


        /* Paging */
        $top = (isset($get['iDisplayStart'])) ? ((int) $get['iDisplayStart'] ) : 0;
        $limit = (isset($get['iDisplayLength'])) ? ((int) $get['iDisplayLength'] ) : 10;


        $sQuery = "SELECT TOP $limit " . implode(",", $this->aColumns) . "
                    FROM $this->sTable
                    $sWhere " . (($sWhere == "") ? " WHERE " : " AND ") . " $this->sIndexColumn NOT IN
                    (
                            SELECT $this->sIndexColumn FROM
                            (
                                    SELECT TOP $top " . implode(",", $this->aColumns) . "
                                    FROM $this->sTable
                                    $sWhere
                                    $sOrder

                            )
                            as [virtTable]
                    )
                    $sOrder";

        //$rs = new DB();
        //$rs->query($sQuery);
        
        $query = $cn->prepare($sQuery);
        $query->execute();
        $result_query = $query->fetchAll();


        //$rs3 = new DB();
        $sQueryCnt = "SELECT count(*) as TOTAL FROM $this->sTable $sWhere";
        $query2 = $cn->prepare($sQueryCnt);
        $query2->execute();
        $result_query2 = $query2->fetchAll();
        //$rs3->query($sQueryCnt);
        $iFilteredTotal = $result_query2[0]['TOTAL'];



       // $rs2 = new DB();
        $sQuery = " SELECT count(*) as TOTAL FROM $this->sTable ";
       // $rs2->query($sQuery);
        $query3 = $cn->prepare($sQuery);
        $query3->execute();
        $result_query3 = $query3->fetchAll();
        $iTotal = $result_query3[0]['TOTAL'];

        $output = array(
            "sEcho" => intval($get['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        //while ($aRow = $rs->getRow()) {   
        foreach ($result_query as $aRow) {
            $row = array();

            for ($i = 0; $i < count($this->aColumns); $i++) {
                if ($this->aColumns[$i] != ' ') {

                    /*                     * ************** PARA MAYORES A 255 *************** */
                    if ($this->aColumns[$i] == 'version') {

                        
                    } else {
                        $v = $aRow[$this->aColumnsName[$i]];
                    }
                    /*                     * ******************************************* */
                    $row[] = $v;
                }
            }
            If (!empty($row)) {
                $output['aaData'][] = $row;
            }
        }
       return $output;
    }

}

?>
