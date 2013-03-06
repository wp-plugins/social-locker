var statisticContext = {};

(function($){
    
    statisticContext = {
        
        // default data that is used when data for the method drawChart is not specified
        defaultData: null,
        
        // default chart options that will be changed when the chart is drawing
        defaultOptions: {
            isStacked: true,
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
            
            this.defaultData = defaultChartData;
            this.resetToDefault();
            
            $("#chart-type-group").find("button").click(function(){
                var type = $(this).data('value');
                if ( window.localStorage ) window.localStorage.setItem('admin-sociallocker-chart-type', type);
                self.drawChart(null, type);
            });
            
            if ( window.localStorage ) {
                var type = localStorage.getItem('admin-sociallocker-chart-type');
                var btnType = '.type-' + type;
                $("#chart-type-group").find("button.active").removeClass('active');
                $("#chart-type-group").find(btnType).click();
            }
            
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
            
            $(window).resize(function(){
                self.drawChart();
            });
        },
        
        resetToDefault: function() {
            this.currentType = null;
            this.currentData = null;
        },
        
        drawChart: function (data, type) {
            
            type = type || this.currentType || 'total';
            data = data || this.currentData || this.defaultData;

            this.currentType = type;
            this.currentData = data;
            
            data = data[type];
            
            if (!type) type = 'total';
            if (!data) data = this.defaultData[type];
            
            var options = $.extend({}, this.defaultOptions);
            var chartFunction = 'LineChart';
            
            // Create the data table.
            var dataTable = new google.visualization.DataTable();
            dataTable.addColumn('date', 'Date');
        
            if (type == 'total') {
                chartFunction = 'AreaChart';
                options.areaOpacity = 0.1;
                options.colors = ['#4691b1'];
                
                dataTable.addColumn('number', 'Total: likes, tweets, +1s');
                dataTable.addColumn({type:'string',role:'tooltip'});
            
            } else if (type == 'detailed') {
                options.colors = ['#7089be', '#3ab9e9', '#e26f61'];  
                //chartFunction = 'ColumnChart';
                dataTable.addColumn('number', 'Likes');
                dataTable.addColumn('number', 'Tweets');             
                dataTable.addColumn('number', '+1s');
                
            } else if (type == 'helpers') {
                chartFunction = 'ColumnChart';
                options.colors = ['#4691b1', '#333', '#ddd'];    
                
                dataTable.addColumn('number', 'Total: likes, tweets, +1s');
                dataTable.addColumn('number', 'by Timer');
                dataTable.addColumn('number', 'by Close Icon'); 
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
