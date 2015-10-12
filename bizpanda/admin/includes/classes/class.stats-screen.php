<?php

class OPanda_StatsScreen {
    
    public function __construct( $options ) {
        $this->options = $options;
    }
    
    public function getChart( $options ) {
        require_once (OPANDA_BIZPANDA_DIR . '/admin/includes/stats.php');
                
        $chartData = OPanda_Stats::getChartData( $options );
        return new $this->options['chartClass']( $this, $chartData );
    }
    
    public function getTable( $options ) {
        require_once (OPANDA_BIZPANDA_DIR . '/admin/includes/stats.php');
        
        $tableData = OPanda_Stats::getViewTable( $options );
        return new $this->options['tableClass']( $this, $tableData );
    }
}

class OPanda_StatsChart {
    
    public $type = 'area';
    
    public function __construct( $screen, $data ) {
        $this->screen = $screen;
        $this->data = $data;
    }
    
    public function getFields() {
        return array();
    }
    
    public function getSelectors() {
        $fields = $this->getFields();
        unset($fields['aggregate_date']);
        return $fields;
    }
    
    public function hasSelectors() {
        $selectors = $this->getSelectors();
        return !empty( $selectors );
    }
    
    public function getSelectorsNames() {
        $selectors = $this->getSelectors();
        if ( empty( $selectors ) ) return array();
        
        $result = array();
        foreach( $selectors as $key => $selector ) {
            $result[] = "'" . $key . "'";
        }

        return $result;
    }
    
    public function printData() {
        $fields = $this->getFields();
        $output = '';
        
        foreach ( $this->data as $rowIndex => $dataRow ) {
   
            $dataToPrint = array();
            foreach( $fields as $field => $fieldData) {

                if ( 'aggregate_date' == $field ) {

                    $dataToPrint['date'] = array( 
                        'value' => 'new Date('.$dataRow['year'].','.$dataRow['mon'].','.$dataRow['day'].')'
                    );
                    
                } else {

                    $dataToPrint[$field] = array(
                        'value' => $this->getValue( $rowIndex, $field ),
                        'title' => isset( $fieldData['title'] ) ? $fieldData['title'] : '',
                        'color' => isset( $fieldData['color'] ) ? $fieldData['color'] : null
                    );
                }
            } 
            
            $rowDataToPrint = '';  
            foreach( $dataToPrint as $key => $data ) { 
                if ( !isset( $data['title'] )) $data['title'] = '';
                if ( !isset( $data['color'] )) $data['color'] = '';

                $rowDataToPrint .= "'$key': {'value': {$data['value']}, 'title': '{$data['title']}', 'color': '{$data['color']}'},";
            }    
            
            $rowDataToPrint = rtrim($rowDataToPrint, ',');
            $output .= '{' . $rowDataToPrint . '},';
        }

        $output = rtrim($output, ',');
        echo $output;
    }
    
    public function getValue( $rowIndex, $fieldName  ) {
        
        $camelCase = str_replace('-', ' ', $fieldName);
        $camelCase = str_replace('_', ' ', $camelCase);    
        $camelCase = str_replace(' ', '', ucwords( $camelCase) );
        
        $camelCase[0] = strtoupper($camelCase[0]);

        if ( method_exists( $this, 'field' . $camelCase ) ) {
            return call_user_func( array( $this, 'field' . $camelCase ), $this->data[$rowIndex], $rowIndex );
        } else {
            if ( isset( $this->data[$rowIndex][$fieldName] ) ) {
                return $this->data[$rowIndex][$fieldName];
            } else {
                return 0;
            }
        }
    }
}

class OPanda_StatsTable {
    
    public $orderBy = 'unlock';
    
    public function __construct( $screen, $data ) {
        $this->screen = $screen;
        $this->data = $data;
        
        usort($data['data'], array( $this, '_usort') );
        $this->data['data'] = array_reverse($data['data']);
    }
    
    public function _usort( $a, $b ) {
        $orderBy = $this->orderBy;
        
        if ( !isset( $a[$orderBy] ) && !isset( $b[$orderBy] ) ) return 0;
        if ( !isset( $a[$orderBy] ) ) return -1; 
        if ( !isset( $b[$orderBy] ) ) return 1;
        
        if ( $a[$orderBy] == $b[$orderBy] ) return 0;
        return ($a[$orderBy] < $b[$orderBy]) ? -1 : 1;
    }

    public function getColumns() {
        return array();
    }
    
    public function getHeaderColumns( $level = 1 ) {
        
        $columns = $this->getColumns();
        
        if ( 2 ===  $level) {
            
            $result = array();
            foreach( $columns as $column ) {
                if ( !isset( $column['columns'] ) ) continue;
                $result = array_merge( $result, $column['columns'] );
            } 
            
            return $result;
            
        } else {
            
            foreach( $columns as $n => $column ) {
                $columns[$n]['rowspan'] = isset( $column['columns'] ) ? 1 : 2;
                $columns[$n]['colspan'] = isset( $column['columns'] ) ? count( $column['columns'] ) : 1;
            }
            
            return $columns;
        }
    } 
    
    public function hasComplexColumns() {
        $columns = $this->getHeaderColumns( 2 );
        return !empty( $columns );
    }
    
    public function getDataColumns() {
        $result = array();
        
        foreach( $this->getColumns() as $name => $column ) {
            
            if ( isset( $column['columns'] ) ) {
                $result = array_merge( $result, $column['columns'] );
            } else {
                $result[$name] = $column;
            }
        }

        return $result;
    }
    
    public function getColumnsCount() {
        return count( $this->getColumns() ); 
    }
    
    public function getRowsCount() {
        return count( $this->data['data'] );
    }
    
    public function printValue( $rowIndex, $columnName, $column ) {
        
        $camelCase = str_replace('-', ' ', $columnName);
        $camelCase = str_replace('_', ' ', $camelCase);    
        $camelCase = str_replace(' ', '', ucwords( $camelCase) );
        
        $camelCase[0] = strtoupper($camelCase[0]);

        if ( method_exists( $this, 'column' . $camelCase ) ) {
            call_user_func( array( $this, 'column' . $camelCase ), $this->data['data'][$rowIndex], $rowIndex );
        } else {
            $value = isset( $this->data['data'][$rowIndex][$columnName] ) ? $this->data['data'][$rowIndex][$columnName] : 0;
            if ( isset( $column['prefix'] ) && $value !== 0 ) echo $column['prefix'] ;
            echo $value;
        }
    }
    
    public function columnIndex( $row, $rowIndex ) {
        echo $rowIndex + 1;
    } 
    
    public function columnTitle( $row ) {
        $title = !empty( $row['title'] ) ? $row['title'] : '<i>' . __('(untitled post)', 'bizpanda') . '</i>';
        
        if ( !empty( $row['id'] ) ) {
            echo '<a href="' . get_permalink( $row['id'] ) . '" target="_blank">' . $title . ' </a>';
        } else {
            echo $title;
        }
    }
    
    public function columnConversion( $row ) {
        if ( !isset( $row['impress'] )) $row['impress'] = 0;
        if ( !isset( $row['unlock'] )) $row['unlock'] = 0;
        
        if ( $row['impress'] == 0 ) { echo '0%'; return;}
        if ( $row['unlock'] > $row['impress']  ) { echo '100%'; return;}

        echo ( ceil( $row['unlock'] / $row['impress']  * 10000 ) / 100 ) . '%';
    }
}