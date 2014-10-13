if ( !window.onpsl ) window.onpsl = {};
if ( !window.onpsl.statistics ) window.onpsl.statistics = {};

(function($){
    
    window.onpsl.statistics = {
        
        // available charts
        availableButtons: {
            "facebook-like": '#7089be',
            "facebook-share": '#566a93', 
            "twitter-tweet": '#3ab9e9', 
            "twitter-follow": '#1c95c3', 
            "google-plus": '#e26f61', 
            "google-share": '#ba5145', 
            "linkedin-share": '#006080',
            "vk-like": '#517296', 
            "vk-share": '#435c77',
            "vk-subscribe": '#435c77',
            "ok-klass": '#f3800d'
        },
        
        // charts selected by default
        defaultButtons: [
            "facebook-like", "twitter-tweet", "google-plus"
        ],
        
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
            //curveType: 'function',
            tooltip: {
                showColorCode: true,
                textStyle: {fontSize: '11', color: '#333'}
            },
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

            this.initButtonSelector();
            this.initTypeSelector();
            this.initDateRangeSelector();

            $(window).resize(function(){
                self.drawChart();
            });
        },
        
        /**
         * Inits buttons selector
         */
        initButtonSelector: function() {
            var self = this;
            
            var activeButtons = this.getPreSelectedButtons();
              
            for(var buttonName in this.availableButtons) {
                var item = $(".onp-sl-chart-item." + buttonName);
                             
                if ( $.inArray( buttonName, activeButtons ) > -1 ) {
                    item.addClass('active');
                    $("#onp-sl-posts .col-" + buttonName).show();
                } else {                    
                    item.removeClass('active');
                    $("#onp-sl-posts .col-" + buttonName).hide();
                }
                
                item.data('button', buttonName);
            }
            
            $(".onp-sl-chart-item").click(function(){
                var buttonName = $(this).data("button");

                if ( $(this).hasClass('active') ) {
                    $(this).removeClass('active');
                    $("#onp-sl-posts .col-" + buttonName).hide();
                } else { 
                    $(this).addClass('active');
                    $("#onp-sl-posts .col-" + buttonName).show();
                }

                self.savePreSelectedButtons();
                self.drawChart();
            });
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
        
        getPreSelectedButtons: function() {
            if ( !window.localStorage ) return this.defaultButtons;
            
            var buttons = localStorage.getItem( 'onp-sl-chart-buttons' );
            if ( !buttons ) return this.defaultButtons;
            
            try {
                buttons = JSON.parse( buttons );
                if ( buttons.length === 0 ) return this.defaultButtons;
            } catch(ex) {
                return this.defaultButtons;
            }
            
            return buttons;
        },
        
        /**
         * Inits types selector.
         */
        initTypeSelector: function() {
            var self = this;
            
            $("#onp-sl-type-select button").click(function(){
                var type = $(this).data('value');
                $("#onp-sl-type-select button.active").removeClass("active");
                if ( window.localStorage ) window.localStorage.setItem('admin-sociallocker-chart-type', type);
                $(this).addClass("active");
                self.drawChart(type);
                
                $(document).find(".onp-chart-hint").hide();
                $(document).find(".onp-chart-hint-" + type).fadeIn();
            });

            if ( window.localStorage ) {
                var type = localStorage.getItem('admin-sociallocker-chart-type');
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
         * Returns selected by user social buttons to show.
         */
        getActiveButtons: function() {
            var result = [];
            var activeItems = $(".onp-sl-chart-item.active");
            activeItems.each(function(){
                var className = $(this).data('button');
                result.push(className);
            });
            return result;
        },
        
        drawChart: function (type) {
            var data = [], index, button;
            
            this.currentType = type || this.currentType || 'total';
            var options = $.extend({}, this.defaultOptions);
            var activeButtons = this.getActiveButtons();

            // Create the data table.
            var dataTable = new google.visualization.DataTable();
            dataTable.addColumn('date', 'Date');
        
            if (this.currentType == 'total') {
                chartFunction = 'AreaChart';
                
                options.legend.position = 'in';
                options.areaOpacity = 0.1;
                options.colors = [ window.onpsl.factoryBootstrap325.colors.primaryDark ];
                
                dataTable.addColumn('number', window.onpsl.res.total_social_impact);
                dataTable.addColumn({type:'string',role:'tooltip'});
                
                data = [];
                for(var rowIndex in chartData) {
                    var row = chartData[rowIndex];
                    
                    var totalCount = 0;
                    for(index in activeButtons) {
                        button = activeButtons[index];
                        totalCount += row[button];
                    }
                    
                    var chartRow = [ row['date'], totalCount, '' + totalCount ];
                    data.push(chartRow);
                }

            } else if (this.currentType == 'detailed') {
                chartFunction = 'LineChart';
                
                options.colors = [];
                options.legend.position = 'none';
                
                for(index in activeButtons) {
                    button = activeButtons[index];
                    var color = this.availableButtons[button];

                    options.colors.push(color);
                    dataTable.addColumn('number', '');
                }

                data = [];
                for(var rowIndex in window.chartData) {
                    var row = window.chartData[rowIndex];

                    var chartRow = [ row['date'] ];
                    for(index in activeButtons) {
                        button = activeButtons[index];
                        chartRow.push( row[button] );
                    }
                    data.push(chartRow);
                }

            } else if (this.currentType == 'helpers') {
                chartFunction = 'ColumnChart';
                
                options.legend.position = 'in';
                options.colors = [window.onpsl.factoryBootstrap325.colors.primaryDark, '#333', '#ddd'];    
                
                dataTable.addColumn('number', window.onpsl.res.unlocked_by_buttons);
                dataTable.addColumn('number', window.onpsl.res.unlocked_by_timer);
                dataTable.addColumn('number', window.onpsl.res.unlocked_by_close_icon); 
                
                data = [];
                for(var rowIndex in chartData) {
                    var row = chartData[rowIndex];
                    
                    var totalCount = 0;
                    for(index in activeButtons) {
                        button = activeButtons[index];
                        totalCount += row[button];
                    }
                    
                    var chartRow = [ row['date'], totalCount, row['timer'], row['cross'] ];
                    data.push(chartRow);
                }
            } else if (this.currentType == 'errors') {
                chartFunction = 'AreaChart';
                
                options.legend.position = 'in';
                options.areaOpacity = 0.1;
                options.colors = [ '#e00' ];
                
                dataTable.addColumn('number', window.onpsl.res.na);
                dataTable.addColumn({type:'string',role:'tooltip'});
                
                data = [];
                for(var rowIndex in chartData) {
                    var row = chartData[rowIndex];
                    
                    var totalCount = 0;
                    for(index in activeButtons) {
                        button = activeButtons[index];
                        totalCount += row[button];
                    }
                    
                    var chartRow = [ row['date'], row['na'], '' + row['na'] ];
                    data.push(chartRow);
                }
            }
            

            dataTable.addRows(data);
            
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization[chartFunction](document.getElementById('chart'));
            chart.draw(dataTable, options);
        }
    };

    $(function(){
        window.onpsl.statistics.init();       
    });
    
})(jQuery)
