if ( !window.bizpanda ) window.bizpanda = {};
if ( !window.bizpanda.statistics ) window.bizpanda.statistics = {};

(function($){
    
    window.bizpanda.statistics = {

        // default chart options that will be changed when the chart is drawing
        defaultOptions: {
            isStacked: true,
            fontSize: 11,
            legend: {
                position: 'in',
                format: 'dd MMM'
            },
            pointSize: 7,
            lineWidth: 3,
            tooltip: {
                showColorCode: true,
                textStyle: {fontSize: '11', color: '#333'}
            },
            colors: [],
            hAxis: {
                baselineColor: '#fff',
                gridlines: {color: '#fff'},
                textStyle: {fontSize: '11', color: '#333'},
                format: 'dd MMM'
            },
            vAxis: {
                baselineColor: '#111',
                gridlines: {color: '#f6f6f6'},
                textPosition: 'in',
                textStyle: {fontSize: '11', color: '#333'}
            },
            chartArea: { height: '250', width: '100%', top: 0 }
        },
        
        init: function() {
            var self = this;
            
            this.initLockerSelectorPopup();

            this.initButtonSelector();
            this.initTypeSelector();
            this.initDateRangeSelector();

            $(window).resize(function(){
                self.redrawChart();
            });
        },
        
        initLockerSelectorPopup: function() {
            
            var $popup = $("#opanda-locker-select-popup").appendTo( $("body") );
            if ( $popup.length === 0 ) return;
            
            var $overlap = $("#opanda-locker-select-overlap");
            var $select = $("#opanda-locker-select");
            var $menu = $("#adminmenuback");
                
            $overlap.show();
            $popup.show();
            
            var updatePopupPosition = function(){
                
                var height = $popup.innerHeight();
                var width = $popup.innerWidth();
                var windowWidth = $( window ).width();
                
                var shift = ( $menu.is(":visible") ) ? $menu.innerWidth() : 0;
            
                $popup.css({
                    'marginTop': -parseInt( height / 2 ) + "px",
                    'left': shift + ( ( windowWidth - shift - width ) / 2 ) + "px"
                });
            };

            $(window).resize(function(){
                updatePopupPosition();
            });

            $("#collapse-menu").click(function(){
                updatePopupPosition();
            });
            
            updatePopupPosition();
            
            $("#opanda-locker-select-submit").click(function(){
                var defaultOption = $select.find(":selected").data('default');
                
                if ( defaultOption ) {
                    $popup.remove();
                    $overlap.remove();
                    return;
                }
                
                window.location.href = $select.val();
                return;
            });
        },
        
        /**
         * Inits buttons selector
         */
        initButtonSelector: function() {
            var self = this;
            
            var activeSelectors = this.getDefaultSelectors();
            $(".onp-sl-selector-item")
                .addClass('opanda-inactive')
                .removeClass('opanda-active');
            
            for( var index in activeSelectors) {
                $(".onp-sl-selector-" + activeSelectors[index])
                    .addClass('opanda-active')
                    .removeClass('opanda-inactive');
            }
            
            $(".onp-sl-selector-item").click(function(){
                var selectorName = $(this).data("selector");
                self.toggleSelector( selectorName );
                return false;
            });
        },
        
        /**
         * Toggles a given selector.
         */
        toggleSelector: function( selectorName ) {
            var $item = $(".onp-sl-selector-" + selectorName);
            if ( $item.hasClass('opanda-active') ) this.deactivateSelector( selectorName );
            else this.activateSelector( selectorName );
        },
        
        /**
         * Activates a given selector.
         */
        activateSelector: function( selectorName ) {
            
            $(".onp-sl-selector-" + selectorName)
                .addClass('opanda-active')
                .removeClass('opanda-inactive');
        
            this.redrawChart();
        },
        
        /**
         * Deactivates a given selector.
         */
        deactivateSelector: function( selectorName ) {
            
            $(".onp-sl-selector-" + selectorName)
                .removeClass('opanda-active')
                .addClass('opanda-inactive');
        
            this.redrawChart();
        },
          
        savePreSelectedButtons: function() {
            if ( !window.localStorage ) return;
            
            var buttons = [];
            $(".onp-sl-chart-item.active").each(function(){
                var buttonName = $(this).data("button");
                buttons.push(buttonName);
            });
            localStorage.setItem('onp-sl-chart-buttons', JSON.stringify(buttons) );
        },
        
        /**
         * Returns default selectors.
         */
        getDefaultSelectors: function() {
            if ( !window.localStorage ) return window.opanda_default_selectors;
            
            var selectors = localStorage.getItem( 'opanda-chart-selectors' );
            if ( !selectors ) return window.opanda_default_selectors;
            
            try {
                selectors = JSON.parse( selectors );
                if ( selectors.length === 0 ) return this.opanda_default_selectors;
            } catch(ex) {
                return this.opanda_default_selectors;
            }
            
            return selectors;
        },
        
        /**
         * Inits types selector.
         */
        initTypeSelector: function() {
            var self = this;
            
            $("#onp-sl-type-select button").click(function(){
                var type = $(this).data('value');
                $("#onp-sl-type-select button.active").removeClass("active");
                if ( window.localStorage ) window.localStorage.setItem('admin-optinpanda-chart-type', type);
                $(this).addClass("active");
                self.drawChart(type);
                
                $(document).find(".onp-chart-hint").hide();
                $(document).find(".onp-chart-hint-" + type).fadeIn();
            });

            if ( window.localStorage ) {
                var type = localStorage.getItem('admin-optinpanda-chart-type');
                var btnType = '.type-' + type;
                $("#onp-sl-type-select").find("button.active").removeClass('active');
                $("#onp-sl-type-select").find(btnType).click();
            } 
        },
        
        /**
         * Inits date range selector.
         */
        initDateRangeSelector: function() {
            var self = this;
            
            $('#onp-sl-date-start')
                .datepicker()
                .bind('changeDate', function(ev){
                    $('#onp-sl-date-start').datepicker('hide');
                });
                
            $('#onp-sl-date-end')
                .datepicker()
                .bind('changeDate', function(ev){
                    $('#onp-sl-date-end').datepicker('hide');
                });
                
            $("#onp-sl-apply-dates").click(function(){
                $(this).parents("form").submit();
                return false;
            });
        },
        
        /**
         * Returns currently active selectors.
         */
        getActiveSelectors: function() {
            
            var result = [];
            var activeItems = $(".onp-sl-selector-item.opanda-active");
            
            activeItems.each(function(){
                result.push( $(this).data('selector') );
            });
            
            if ( !result.length ) return false;
            return result;
        },
        
        /**
         * Draws the chart.
         */
        drawChart: function ( params ) {
            var data = [], index, button;
            var options = $.extend({}, this.defaultOptions);
            
            var chartType = params.type || 'line';
            var chartFunction = 'LineChart';
            
            this._params = params;

            if ( 'area' === chartType ) {
                chartFunction = 'AreaChart';
                options.legend.position = 'in';
                options.areaOpacity = 0.1;
            }
            else if ( 'column' === chartType ) {
                options.legend.position = 'none';
                chartFunction = 'ColumnChart';
            }
            else {
                options.legend.position = 'none';
                chartFunction = 'LineChart';
            } 
            
            var activeSelectors = this.getActiveSelectors();

            // Create the data table.
            var dataTable = new google.visualization.DataTable();


            var data = [];
            var columns = [];
            var colors = [];
            
            columns.push({type: 'date', 'title': 'Date'});

            // building the columns and colors

            var row = window.chartData[0];
            for ( var column in row ) {

                if ( 'date' === column ) continue;
                
                // if the page contains selectors
                if ( activeSelectors && $.inArray( column, activeSelectors) === -1 ) continue;

                // column & title
                columns.push({type: 'number', 'title': row[column]['title']});

                var color = 'PRIMARY_DARK' === row[column]['color']
                        ? window.factory.factoryBootstrap329.colors.primaryDark
                        : row[column]['color'];

                colors.push(color);
            }

            // building the data array
            
            var data = [];
            for( var rowIndex in window.chartData ) {
                var row = window.chartData[rowIndex];

                var chartRow = [ row['date']['value'] ];
                for ( var column in row ) {

                    if ( 'date' === column ) continue;
                    
                    // if the page contains selectors
                    if ( activeSelectors && $.inArray( column, activeSelectors) === -1 ) continue;

                    chartRow.push( row[column]['value'] );
                }
                data.push(chartRow);
            }

            for( var i in columns ) dataTable.addColumn( columns[i].type, columns[i].title );
            options.colors = colors; 
            dataTable.addRows(data);

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization[chartFunction](document.getElementById('chart'));
            chart.draw(dataTable, options);
        },
        
        redrawChart: function() {
            this.drawChart( this._params );
        }
    };

    $(function(){
        window.bizpanda.statistics.init();       
    });
    
})(jQuery)
