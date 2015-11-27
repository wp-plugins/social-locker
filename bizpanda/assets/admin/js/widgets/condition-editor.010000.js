if ( !window.bp ) window.bp = {};

(function($){
    
    /**
     * Condition Editor
     */
    $.widget( "bp.bpConditionEditor", {
               
        options: {
            filters: null
        },
               
        _create: function() {
            var self = this;
            
            this._counter = 0;
            
            this._$editor = this.element;
            this._$editor.data('bp-api', this);
            
            this._$filters = this._$editor.find(".bp-filters");  
            this._$tmplFilter = this._$editor.find(".bp-filter.bp-template").clone().removeClass("bp-template");
            
            this._$btnAdd = this._$editor.find(".bp-add-filter");
            this._$btnAdd.click(function(){ self.addFilter(); return false; });

            this._$editor.on('bp.filters-changed', function(){
                self._checkIsEmpty();
            });
            
            this._load();
            
            this._checkIsEmpty();
        },
        
        _load: function() {

            if ( this.options.filters ) {
                for ( var index in this.options.filters ) {
                    this.addFilter( this.options.filters[index]);
                }
            }
        },
        
        _checkIsEmpty: function() {
            
            if ( this.getCount() === 0 ) {
                this._$editor.addClass('bp-empty');
            } else {
                this._$editor.removeClass('bp-empty');
            }
        },
        
        addFilter: function( data ) {
            if ( !data ) data = {type: 'showif'};
            
            var self = this;

            var $filter = this._$tmplFilter.clone();
            this._$filters.append( $filter );

            $filter.data('bp-editor', this._$editor);
            
            this._counter = this._counter + 1;
            
            $filter.bpConditionFilter({
                index: self._counter,
                type: data.type,
                conditions: data.conditions
            });
            
            self._$editor.trigger('bp.filters-changed');
            return $filter;
        },

        getData: function() {
            var filters = [];
            
            this._$filters.find(".bp-filter").each(function(){
                var definition = $(this).bpConditionFilter('getData');
                filters.push(definition);
            });
            
            return filters;
        },
        
        getCount: function() {
            return this._$editor.find('.bp-filter:not(.bp-template)').length;
        }
    });
    
    /**
     * Condition Filter
     */
    $.widget( "bp.bpConditionFilter", {
        
        options: {
            type: 'showif',
            conditions: null,
            index: null
        },
        
        _create: function() {
            var self = this;
            
            this._counter = 0;
            this._index = this.options.index;
            
            this._$filter = this.element;
            this._$filter.data('bp-api', this);
            
            this._$editor = this._$filter.data('bp-editor');
            this._$conditions = this._$filter.find(".bp-conditions");
            
            this._$tmplCondition = this._$editor.find(".bp-condition.bp-template").clone().removeClass("bp-template");
            this._$tmplScope = this._$editor.find(".bp-scope.bp-template").clone().removeClass("bp-template");

            this._load();

            this._$filter.find(".bp-link-add").click(function(){
                self.addCondition();
                return false;
            });
            
            this._$filter.find(".btn-remove-filter").click(function(){
                self._$filter.remove();
                self._$editor.trigger('bp.filters-changed');
                return false;
            });
            
            this._$filter.find(".bp-btn-apply-template").click(function(){
                var templateName = $(".bp-select-template").val();
                
                if ( templateName ) {
                    var data = self.getTemplateData(templateName);
                    if ( data ) self.setFilterData( data );
                }
                
                return false;
            });            
            
            this._$filter.on('bp.conditions-changed', function(){
                self._checkIsEmpty();
            });
        },
        
        _load: function() {

            if ( !this.options.conditions ) {
                this.addCondition();
            } else {
                this.setFilterData(this.options);
            } 
        },
        
        setFilterData: function( data ) {

            this._$filter.find('.bp-condition').remove();
            
            if ( data.conditions ) {
                for ( var index in data.conditions ) {
                    this.addCondition( data.conditions[index] );
                }
            } 
            
            this._$filter.find(".bp-filter-type").val(data.type);
            this._checkIsEmpty();
        },

        _checkIsEmpty: function() {
            
            if ( this.getCount() === 0 ) {
                this._$filter.addClass('bp-empty');
            } else {
                this._$filter.removeClass('bp-empty');
            }

            this._$conditions.find('.bp-scope').each(function(){
                var count = $(this).find('.bp-condition').length;
                if ( count === 0 )$(this).remove();
            });
        },
        
        addCondition: function( data, $scope ) {
            if ( !data ) data = {type: 'condition'};

            if ( data.type === 'scope' ) {
                this.addScope( data );
            } else if ( data.type === 'condition' && !$scope ) {
                var $scope = this.addScope();
                this.addCondition( data, $scope );
            } else {
                
                var $condition = this._$tmplCondition.clone();
                $scope.append( $condition );
                
                $condition.data('bp-scope', $scope);              
                $condition.data('bp-editor', this._$editor);
                $condition.data('bp-filter', this._$filter);
                
                this._counter = this._counter + 1;
                data.index = this._index + '_' + this._counter;
                
                $condition.bpCondition( data ); 
                this._$filter.trigger('bp.conditions-changed');
            }
        },

        addScope: function( data ) {
            if ( !data ) data = {};
            
            var $scope = this._$tmplScope.clone();
            this._$conditions.append($scope);
            
            if ( data && data.conditions ) {
                for ( var index in data.conditions ) {
                    this.addCondition( data.conditions[index], $scope );
                }
            }
            
            return $scope;            
        },
        
        getData: function() {
            var scopes = [];
            
            this._$conditions.find('.bp-scope').each(function(){
                
                var scope = {
                    type: 'scope',
                    conditions: []
                };
                
                scopes.push(scope);
                
                $(this).find('.bp-condition').each(function(){
                    var condition = $(this).bpCondition('getData');
                    scope.conditions.push( condition );
                });
            });
            
            var filterType = this._$filter.find(".bp-filter-type").val();
            
            return {
                conditions: scopes,
                type: filterType
            };
        },
        
        getCount: function() {
            return this._$filter.find('.bp-condition').length;
        },

        getTemplateData: function( paramName ) {
            if ( !window.bp ) return;
            if ( !window.bp.templates ) return;

            for ( var index in window.bp.templates ) {
                var data = window.bp.templates[index];
                if ( data['id'] === paramName ) return data['filter'];
            }

            return false; 
        }
    });
    
    /**
     * Condition
     */
    $.widget( "bp.bpCondition", {
        
        options: {
            index: null
        },
        
        _create: function() {
            this._index = this.options.index;

            this._$condition = this.element;
            this._$condition.data('bp-condition', this);
            
            this._$editor = this._$condition.data('bp-editor');
            this._$filter = this._$condition.data('bp-filter');
            this._$scope = this._$condition.data('bp-scope');
            
            this._editor = this._$editor.data('bp-api');
            this._filter = this._$filter.data('bp-api');
  
            this._$hint = this.element.find(".bp-hint");
            this._$hintContent = this.element.find(".bp-hint-content");
            
            this._$tmplDateControl = this._$editor.find(".bp-date-control.bp-template").clone().removeClass("bp-template");
        },
        
        _init: function() {
            var self = this;

            this._$condition.find(".bp-param-select").change(function(){ self.prepareFields(); });
            self.prepareFields( true ); 
            
            // buttons
            
            this._$condition.find(".bp-btn-remove").click(function(){
                self.remove();
                return false;
            });

            this._$condition.find(".bp-btn-or").click(function(){
                self._filter.addCondition( null, self._$scope );
                return false;
            });
            
            this._$condition.find(".bp-btn-and").click(function(){
                self._filter.addCondition();
                return false;
            }); 
        },
        
        remove: function() {
            this._$condition.remove();
            this._$filter.trigger('bp.conditions-changed');
        },
        
        getData: function() {
            
            var currentParam = this._$condition.find(".bp-param-select").val();
            var paramOptions = this.getParamOptions( currentParam );
            
            var $operator = this._$condition.find(".bp-operator-select");
            var currentOperator = $operator.val();
            
            var value = null;
            
            if ( 'select' === paramOptions['type'] ) {
                value = this.getSelectValue( paramOptions );
            } else if ( 'date' === paramOptions['type'] ) {           
                value = this.getDateValue( paramOptions );
            } else if ( 'integer' === paramOptions['type'] ) {           
                value = this.getIntegerValue( paramOptions );  
            } else {
                value = this.getTextValue( paramOptions );    
            }
            
            return {
                param: currentParam,
                operator: currentOperator,
                type: paramOptions['type'],
                value: value
            };
        },
        
        prepareFields: function( isInit ) {
            var self = this;

            if ( isInit && this.options.param ) {
                this.selectParam( this.options.param );
            }

            var currentParam = this._$condition.find(".bp-param-select").val();
            var paramOptions = this.getParamOptions( currentParam );
            
            this.setParamHint(paramOptions.description);
            
            var operators = [];
            
            if ( 'select' === paramOptions['type'] ) {
                operators = ['equals', 'notequal'];
            } else if ( 'date' === paramOptions['type'] ) {           
                operators = ['equals', 'notequal', 'younger', 'older', 'between'];
            } else if ( 'integer' === paramOptions['type'] ) {           
                operators = ['equals', 'notequal', 'less', 'greater', 'between'];
            } else {
                operators = ['equals', 'notequal', 'contains', 'notcontain'];
            }

            this.setOperators(operators);
            
            if ( isInit && this.options.operator ) {
                this.selectOperator( this.options.operator );
            }
            
            this.createValueControl( paramOptions, isInit );
        },
        
        /**
         * Displays and configures the param hint.
         */
        setParamHint: function( description ) {
            
            if ( description ) {
                this._$hintContent.html(description);
                this._$hint.show();
            } else {
                this._$hint.hide(); 
            }
        },
        
        /**
         * Creates control to specify value.
         */
        createValueControl: function( paramOptions, isInit ) {

            if ( 'select' === paramOptions['type'] ) {
                this.createValueAsSelect( paramOptions, isInit );
            } else if ( 'date' === paramOptions['type'] ) {           
                this.createValueAsDate( paramOptions, isInit );
            } else if ( 'integer' === paramOptions['type'] ) {           
                this.createValueAsInteger( paramOptions, isInit );  
            } else {
                this.createValueAsText( paramOptions, isInit );    
            } 
        },
        
        // -------------------
        // Select Control
        // -------------------
        
        /**
         * Creates the Select control.
         */
        createValueAsSelect: function( paramOptions, isInit ) {
            var self = this;
            
            var createSelect = function( values ) {
                var $select = self.createSelect( values );
                self.insertValueControl( $select );
                if ( isInit && self.options.value ) self.setSelectValue( self.options.value );
            };
            
            if ( !paramOptions['values'] ) return;            
            if ( 'ajax' === paramOptions['values']['type'] ) {
            
                var $fakeSelect = self.createSelect( [{value: null, title: '- loading -'}] );
                self.insertValueControl( $fakeSelect );

                $fakeSelect.attr('disabled', 'disabled');
                $fakeSelect.addClass('bp-fake-select');
                
                if ( isInit && this.options.value ) {
                    $fakeSelect.data('value', this.options.value);
                }

                var req = $.ajax({
                    url: window.ajaxurl,
                    data: {
                        action: paramOptions['values']['action']
                    },
                    dataType: 'json',
                    success: function( data ) {
                        
                        if ( data.error ) {
                            self.advancedOptions.showError( data.error );
                            return;
                        } else if ( !data.values ) {
                            self.advancedOptions.showError( req.responseText );
                            return;
                        }

                        createSelect( data.values );
                    },
                    error: function() {
                        self.advancedOptions.showError( 'Unexpected error during the ajax request.' );
                    },
                    complete: function() {   
                        if ( $fakeSelect ) $fakeSelect.remove();
                        $fakeSelect = null;
                    }
                });
            } else {
                createSelect( paramOptions['values'] );
            }
        },
        
        /**
         * Returns a value for the select control.
         */
        getSelectValue: function() {
            var $select = this._$condition.find(".bp-value select");
            
            var value = $select.val();
            if ( !value ) value = $select.data('value');
            return value;
        },
        
        /**
         * Sets a select value.
         */
        setSelectValue: function( value ) {
            var $select = this._$condition.find(".bp-value select");
            
            if ( $select.hasClass('.bp-fake-select') ) {
                $select.data('value', value);
            } else {
                $select.val(value);   
            }
        },
        
        // -------------------
        // Date Control
        // -------------------

        /**
         * Creates a control for the input linked with the date.
         */
        createValueAsDate: function( paramOptions, isInit ) {
            
            var $operator = this._$condition.find(".bp-operator-select");
            var $control = this._$tmplDateControl.clone();
            
            $operator.change(function(){
                var currentOperator = $operator.val();
            
                if ( 'between' === currentOperator ) {
                    $control.addClass('bp-between');
                    $control.removeClass('bp-solo');  
                } else {
                    $control.addClass('bp-solo');
                    $control.removeClass('bp-between');  
                }

            });
          
            $operator.change();
            
            var $radioes = $control.find(".bp-switcher input")
                .attr('name', 'bp_switcher_' + this._index)
                .click(function(){
                    var value = $control.find(".bp-switcher input:checked").val();
                    if ( 'relative' === value ) {
                        $control.addClass('bp-relative');
                        $control.removeClass('bp-absolute'); 
                    } else {
                        $control.addClass('bp-absolute');
                        $control.removeClass('bp-relative');     
                    }
                });
                
            $control.find(".bp-absolute-date input[type='text']").datepicker({
                format: 'dd.mm.yyyy',
                todayHighlight: true,
                autoclose: true
            });

            this.insertValueControl( $control );
            if ( isInit && this.options.value ) this.setDateValue( this.options.value );
        },
        
        /**
         * Returns a value for the Date control.
         * @returns {undefined}
         */
        getDateValue: function() {
            var value = {};
            
            var $operator = this._$condition.find(".bp-operator-select");
            var currentOperator = $operator.val();
            
            var $control = this._$condition.find(".bp-value > .bp-date-control");
            var $holder = this._$condition.find(".bp-value > .bp-date-control");
                    
            if ( 'between' === currentOperator ) {
                $holder = $holder.find(".bp-between-date");
                value.range = true;
                
                value.start = {};
                value.end = {};
                
                if ( $control.hasClass('bp-relative') ) {
                    $holder = $holder.find(".bp-relative-date");  

                    value.start.unitsCount = $holder.find(".bp-date-value-start").val();
                    value.end.unitsCount = $holder.find(".bp-date-value-end").val();
                    
                    value.start.units = $holder.find(".bp-date-start-units").val();
                    value.end.units = $holder.find(".bp-date-end-units").val();     
                    
                    value.start.type = 'relative';
                    value.end.type = 'relative';  
                    
                } else {
                    $holder = $holder.find(".bp-absolute-date");
                    
                    value.start = $holder.find(".bp-date-value-start").datepicker('getUTCDate').getTime();  
                    value.end = $holder.find(".bp-date-value-end").datepicker('getUTCDate').getTime();
                    value.end = value.end + ( ( ( 23 * 60 * 60 ) + ( 59 * 60 ) + 59 ) * 1000 ) + 999;
                }
                
            } else {
                $holder = $holder.find(".bp-solo-date");
                value.range = false;
                
                if ( $control.hasClass('bp-relative') ) {
                    $holder = $holder.find(".bp-relative-date");
                    
                    value.type = 'relative'; 
                    value.unitsCount = $holder.find(".bp-date-value").val();
                    value.units = $holder.find(".bp-date-value-units").val(); 
                    
                } else {
                    $holder = $holder.find(".bp-absolute-date");
                    value = $holder.find("input[type='text']").datepicker('getUTCDate').getTime();

                    if ( 'older' === currentOperator ) {
                        value = value + ( ( ( 23 * 60 * 60 ) + ( 59 * 60 ) + 59 ) * 1000 ) + 999;
                    }
                }
            }
            
            return value;
        },
        
        /**
         * Sets a select value.
         */
        setDateValue: function( value ) {
            if ( !value ) value = {};
            
            var $holder = this._$condition.find(".bp-value > .bp-date-control");
            var $control = this._$condition.find(".bp-value > .bp-date-control");
            
            if ( value.range ) {
                
                if ( 'relative' === value.start.type ) {
                    $holder = $holder.find(".bp-relative-date");
                    
                    $holder.find(".bp-date-value-start").val(value.start.unitsCount);
                    $holder.find(".bp-date-value-end").val(value.end.unitsCount);
                    $holder.find(".bp-date-start-units").val(value.start.units);
                    $holder.find(".bp-date-end-units").val(value.end.units);  

                } else {
                    $holder = $holder.find(".bp-absolute-date");
                    
                    var start = new Date(value.start);
                    var end = new Date(value.end);
                    
                    $holder.find(".bp-date-value-start").datepicker('setUTCDate', start);  
                    $holder.find(".bp-date-value-end").datepicker('setUTCDate', end);     
                }
                
            } else {
                
                if ( 'relative' === value.type ) {
                    $holder = $holder.find(".bp-relative-date");   
                    
                    $holder.find(".bp-date-value").val(value.unitsCount);
                    $holder.find(".bp-date-value-units").val(value.units);
                    
                } else {
                    $holder = $holder.find(".bp-absolute-date");

                    var date = new Date(value);
                    $holder.find(".bp-date-value").datepicker('setUTCDate', date);
                }
            }
            
            var $relative = $control.find(".bp-switcher input[value=relative]");
            var $absolute = $control.find(".bp-switcher input[value=absolute]");
            
            if ( 'relative' === value.type || ( value.start && 'relative' === value.start.type ) ) {
                $relative.attr('checked', 'checked');
                $relative.click();
            } else {
                $absolute.attr('checked', 'checked');
                $absolute.click();
            }
        },
        
        // -------------------
        // Integer Control
        // -------------------
        
        /**
         * Creates a control for the input linked with the integer.
         */
        createValueAsInteger: function( paramOptions, isInit ) {
            var self = this;

            var $operator = this._$condition.find(".bp-operator-select");
            
            $operator.on('change', function(){
                var currentOperator = $operator.val();
                
                var $control;
                if ( 'between' === currentOperator ) {
                    $control = $("<span><input type='text' class='bp-integer bp-integer-start' /> and <input type='text' class='bp-integer bp-integer-end' /></span>");
                } else {
                    $control = $("<input type='text' class='bp-integer bp-integer-solo' /></span>");
                }
                
                self.insertValueControl( $control ); 
            });
          
            $operator.change();
            if ( isInit && this.options.value ) this.setIntegerValue( this.options.value );
        },
        
        /**
         * Returns a value for the Integer control.
         */
        getIntegerValue: function() {
            var value = {};
            
            var $operator = this._$condition.find(".bp-operator-select");
            var currentOperator = $operator.val();
            
            if ( 'between' === currentOperator ) {
                value.range = true;
                value.start = this._$condition.find(".bp-integer-start").val();
                value.end = this._$condition.find(".bp-integer-end").val();

            } else {
                value = this._$condition.find(".bp-integer-solo").val();
            }
            
            return value;
        },
        
        /**
         * Sets a value for the Integer control.
         */
        setIntegerValue: function( value ) {
            if ( !value ) value = {};
            
            if ( value.range ) {
                this._$condition.find(".bp-integer-start").val(value.start);
                this._$condition.find(".bp-integer-end").val(value.end);
            } else {
                this._$condition.find(".bp-integer-solo").val(value);
            }
        },
        
        // -------------------
        // Text Control
        // -------------------
        
        /**
         * Creates a control for the input linked with the integer.
         */
        createValueAsText: function( paramOptions, isInit ) {

            var $control = $("<input type='text' class='bp-text' /></span>");
            this.insertValueControl( $control ); 
            if ( isInit && this.options.value ) this.setTextValue( this.options.value );
        },
        
        /**
         * Returns a value for the Text control.
         * @returns {undefined}
         */
        getTextValue: function() {
            return this._$condition.find(".bp-text").val();
        },
        
        /**
         * Sets a value for the Text control.
         */
        setTextValue: function( value ) {
            this._$condition.find(".bp-text").val(value);
        },
        
        // -------------------
        // Helper Methods
        // -------------------
        
        selectParam: function( value ) {
            this._$condition.find(".bp-param-select").val(value);
        },
        
        selectOperator: function( value ) {
            this._$condition.find(".bp-operator-select").val(value);
        },
        
        setOperators: function( values ) {
            var $operator = this._$condition.find(".bp-operator-select");
            $operator.off('change');
            
            $operator.find("option").hide();
            for ( var index in values ) {
                $operator.find("option[value='" + values[index] + "']").show();
            }
        },
        
        insertValueControl: function( $control ) {
            this._$condition.find(".bp-value").html("").append($control);
        },
        
        getParamOptions: function( paramName ) {
            if ( !window.bp ) return;
            if ( !window.bp.filtersParams ) return;

            for ( var index in  window.bp.filtersParams ) {
                var paramOptions = window.bp.filtersParams[index];
                if ( paramOptions['id'] === paramName ) return paramOptions;
            }

            return false; 
        },

        createSelect: function( values, attrs ) {

            var $select = $("<select></select>");
            if ( attrs ) $select.attr( attrs );

            for ( var index in values ){

                var item = values[index];

                var $option = $("<option></option>")
                        .attr('value', item['value'])
                        .text(item['title']);

                $select.append( $option );
            }

            return $select;
        },
         
         createDataPircker: function() {
             
            var $control = $('<div class="bp-date-control" data-date="today"></div>');
            var $input = $('<input size="16" type="text" readonly="readonly" />');
            var $icon = $('<i class="fa fa-calendar"></i>');

            $control.append($input);
            $control.append($icon);

            var $datepicker = $input.datepicker({
                autoclose: true,
                format: 'dd/mm/yyyy'
            });

            $control.data('bp-datepicker', $datepicker);

            $icon.click(function(){
                $input.datepicker('show');
            });

            $control.on('changeDate', function(ev){
                $input.datepicker('hide');
            });
            
            return $control;
         }
    });
    
})(jQuery);

