<?php
/**
 * The file contains the class of Columns Holder.
 * 
 * @author Paul Kashtanoff <paul@byonepress.com>
 * @copyright (c) 2014, OnePress Ltd
 * 
 * @package factory-forms 
 * @since 1.0.0
 */

/**
 * Columns Holder
 * 
 * @since 1.0.0
 */
class FactoryForms328_ColumnsHolder extends FactoryForms328_Holder {
    
    /**
     * A holder type.
     * 
     * @since 1.0.0
     * @var string
     */
    public $type = 'columns';
        
    public function __construct($options, $form) {
        $columnsItems = array();
        
        $items = isset( $options['items'] ) ? $options['items'] : array();
        
        // calculates the number of columns
        
        $this->columnsCount = 0;
        
        foreach( $options['items'] as $item ) {
            $i = ( !isset( $item['column'] ) ? 1 : intval( $item['column'] ) ) - 1;
            $columnsItems[$i][] = $item;
            
            if ( $i > $this->columnsCount ) $this->columnsCount = $i + 1;
        }
        // calculates the number of rows

        $this->rowsCount = 0;
        foreach($columnsItems as $items) {
            $count = count( $items );
            if ( $count > $this->rowsCount ) $this->rowsCount = $count;
        } 
        
        // creates elements
        
        parent::__construct($options, $form);
        
        // groups the created by columns
        
        $elementIndex = 0;
        $this->columns = array();

        foreach($columnsItems as $columnIndex => $columnItems) {
            $count = count ( $columnItems );
            for ( $k = 0; $k < $count; $k++ ) {
                $this->columns[$columnIndex][] = $this->elements[$elementIndex];
                $elementIndex++;
            }
        }
    }
    
    public function render() {
        $this->beforeRendering();

        for( $n = 0; $n < $this->rowsCount; $n++ ) {

            $this->form->layout->startRow( $n, $this->rowsCount );
            
            for( $i = 0; $i < $this->columnsCount; $i++ ) {
                $control = $this->columns[$i][$n];
                $this->form->layout->startColumn( $control, $i, $this->columnsCount );
                $this->columns[$i][$n]->render();
                $this->form->layout->endColumn( $control, $i, $this->columnsCount );    
            }

            $this->form->layout->endRow( $n, $this->rowsCount ); 
        }
    }
}