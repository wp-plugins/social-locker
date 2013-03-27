var statisticContext = {};

(function($){
    
    statisticContext = {
        
        // available charts
        availableButtons: {
            "facebook-like": '#7089be',
            "facebook-share": '#566a93', 
            "twitter-tweet": '#3ab9e9', 
            "twitter-follow": '#1c95c3', 
            "google-plus": '#e26f61', 
            "google-share": '#ba5145', 
            "linkedin-share": '#006080'
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
                baselineColor: '#000',
                gridlines: {color: '#f6f6f6'},
                textPosition: 'in',
                textStyle: {fontSize: '11', color: '#333'}
            },
            chartArea: { height: '170', width: '100%', top: 0 }
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
            
            // sets check boxes
            var index, className, isActive;
            
            // default values
            for(index in this.defaultButtons) {
                className = this.defaultButtons[index];
                $(".chart-item." + className).addClass('active');
            }
            
            for(className in this.availableButtons) {
                    
                // the previous user selection
                if ( window.localStorage ) {
                
                    isActive = localStorage.getItem('sociallocker-chart-' + className);
                    var item = $(".chart-item." + className);
                    
                    if ( isActive ) {
                        if ( isActive == "yes" ) { 
                            item.addClass('active');
                            $("#posts-table .col-" + className).show();
                        }
                        else { 
                            item.removeClass('active');
                            $("#posts-table .col-" + className).hide();
                        }
                    }
                }
                
                item.data('className', className);
                    
                // and the click event
                item.click(function(){
                    var className = $(this).data('className');
                    
                    if ( $(this).hasClass('active') ) {
                        $(this).removeClass('active');
                        $("#posts-table .col-" + className).hide();
                        if ( window.localStorage ) localStorage.setItem('sociallocker-chart-' + className, "no");
                    } else { 
                        $(this).addClass('active');
                        $("#posts-table .col-" + className).show();
                        if ( window.localStorage ) localStorage.setItem('sociallocker-chart-' + className, "yes");
                    }
                    
                    self.drawChart();
                });
            }
        },
        
        /**
         * Inits types selector.
         */
        initTypeSelector: function() {
            var self = this;
            
            $("#chart-type-group").find("button").click(function(){
                var type = $(this).data('value');
                if ( window.localStorage ) window.localStorage.setItem('admin-sociallocker-chart-type', type);
                self.drawChart(type);
                
                if ( type == "detailed" ) {
                    
                }
            });

            if ( window.localStorage ) {
                var type = localStorage.getItem('admin-sociallocker-chart-type');
                var btnType = '.type-' + type;
                $("#chart-type-group").find("button.active").removeClass('active');
                $("#chart-type-group").find(btnType).click();
            } 
        },
        
        /**
         * Inits date range selector.
         */
        initDateRangeSelector: function() {
            var self = this;
            
            $('#date-start')
                .datepicker()
                .bind('changeDate', function(ev){
                    $('#date-start').datepicker('hide');
                });
                
            $('#date-end')
                .datepicker()
                .bind('changeDate', function(ev){
                    $('#date-end').datepicker('hide');
                });
        },
        
        /**
         * Returns selected by user social buttons stats to show.
         */
        getActiveButtons: function() {
            
            var result = [];
            var activeItems = $(".chart-item.active");
            activeItems.each(function(){
                var className = $(this).data('className');
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
                options.colors = ['#4691b1'];
                
                dataTable.addColumn('number', 'Total social interactions');
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
                options.colors = ['#4691b1', '#333', '#ddd'];    
                
                dataTable.addColumn('number', 'Total social interactions');
                dataTable.addColumn('number', 'by Timer');
                dataTable.addColumn('number', 'by Close Icon'); 
                
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
            }

            dataTable.addRows(data);
            
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization[chartFunction](document.getElementById('chart'));
            chart.draw(dataTable, options);
        }
    };

    $(function(){
        statisticContext.init();
    });
    
})(jQuery)
