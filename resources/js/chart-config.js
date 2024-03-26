$(document).ready(function () {
    const currentDate = new Date();

    // Get the year and month
    const year = currentDate.getFullYear();
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Month is zero-indexed,


    // Set the value of the month input to the current year and month
    document.getElementById('monthInput').value = `${year}-${month}`;
    document.getElementById('monthInputDoughnut').value = `${year}-${month}`;


    const drawColumnDataNumber = {
        id: 'drawColumnDataNumber',
        afterDatasetsDraw: function (chart, args, options) {
            // To only draw at the end of animation, check for easing === 1
            var ctx = chart.ctx;
            chart.data.datasets.forEach(function (dataset, i) {
                var meta = chart.getDatasetMeta(i);
                if (!meta.hidden) {
                    meta.data.forEach(function (element, index) {
                        if (dataset.data[index] <= 0) return;
                        // Draw the text in black, with the specified font
                        ctx.fillStyle = 'rgb(0,0,0)';
                        var fontSize = 16;
                        var fontStyle = 'normal';
                        var fontFamily = 'Helvetica Neue';
                        ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                        // Just naively convert to string for now
                        var dataString = formatPrice(dataset.data[index]).toString();
                        // Make sure alignment settings are correct
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        var padding = 5;
                        var position = element.tooltipPosition();
                        ctx.fillText(dataString, position.x , position.y);
                    });
                }
            });
        }
    };

    var formatPrice = function (value) {
        let val = (value/1).toFixed(0).replace('.', ',')
        return  val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    }

    let salesPurchasesBar = document.getElementById('salesPurchasesChart');
    let overviewChart = document.getElementById('currentDailyChart');
    let overviewChartMonth = document.getElementById('currentMonthChart');

    let salesPurchasesChart = new Chart(salesPurchasesBar, {
        type: 'bar',
        options: {
            animation: {
                duration: 500,
                easing: "easeOutQuart",
                onComplete: function({ chart }) {
                    const ctx = chart.ctx;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    chart.config.data.datasets.forEach(function(dataset, i) {
                        const meta = chart.getDatasetMeta(i);

                        meta.data.forEach(function(bar, index) {
                            const data = dataset.data[index];
                            if (data > 0 ) {
                                ctx.fillText(formatPrice(data), bar.x, bar.y - 5);
                            }
                        });
                    });
                }
            },
            scales: {
                x: {
                    display: true
                },
                y: {
                    stacked: true
                }
            }
            ,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var data = context.dataset.customData[context.dataIndex];
                            var datatotal = context.dataset.data[context.dataIndex];
                            return `Tổng: ${formatPrice(datatotal)} | Tiền mặt: ${formatPrice(data["Cash"])} | Chuyển khoản: ${formatPrice(data["Bank Transfer"])} | Tổng số ly : ${data['Count']}`;
                        }
                    }
                }
            }
        },

    });

    let currentMonthChart = new Chart(overviewChartMonth, {
        type: 'doughnut',
        plugins: [drawColumnDataNumber]
    });

    $.get('/sales-purchases/chart-data', function (response) {
        var label = [];
        var setdataProduct = [];
        var end = new Date().getDate();
        var month = new Date().getMonth();
        for (var i = 1; i<=end;i++){
            label.push(i);
        }

        var datas = [];
        var lables = [];
        var datas_custom = [];
        var dataset = [];
        var dataset_custom = [];


        $.each(label, function (i, item) {
            datas[" " + item] = 0;
            datas_custom[" " + item] = {
                "Bank Transfer": 0,
                "Cash": 0,
                "Count": 0
            };
        })
        response.sales.original.statistics_data.forEach(function (item, idx) {
            if (item.statistic_day !== 0) {
                var date = new Date(item.statistic_day).getDate();
                if (!datas_custom[" " +date]) {
                    datas_custom[" " +date] = {};
                }
                datas_custom[" " + date]['Count'] = parseInt(item.count);
            }
        });

        var total = 0;
        response.sales.original.data.forEach(function (item, idx) {
            var date = item.date;
            date = new Date(item.date).getDate();
            total += parseInt(item.count) / 100;
            if (!datas[" " + date]) {
                datas[" " + date] = parseInt(item.count) / 100;
            } else {
                datas[" " + date] += parseInt(item.count) / 100;
            }
            if (!datas_custom[" " + date]) {
                datas_custom[" " + date] = {};
            }
            datas_custom[" " + date][item.payment_method] = parseInt(item.count) / 100;
        });

        $.each(label, function (i, item) {
            var label_item =  item;
            lables.push(label_item)
            dataset[i] = datas[" " + item] || 0;
            dataset_custom[i] = datas_custom[" " + item] || {
                "Bank Transfer": 0,
                "Cash": 0,
                "Count": 0
            };
        })

        var  data = {
            labels: lables,
            datasets: [{
                label: 'Doanh thu',
                data: dataset,
                customData: dataset_custom,
                backgroundColor: [
                    '#6366F1',
                ],
                borderColor: [
                    '#6366F1',
                ],
                borderWidth: 1
            }
            ]
        }

        salesPurchasesChart.data = data;
        salesPurchasesChart.update();
    });


    $.get('/current-month/chart-data', function (response) {
        let currentDailyChart = new Chart(overviewChart, {
            type: 'doughnut',
            data: {
                labels: ['Ca sáng', 'Ca chiều', 'Ca tối'],
                datasets: [{
                    data: [response.morning, response.afternoon, response.night],
                    backgroundColor: [
                        '#F59E0B',
                        '#0284C7',
                        '#EF4444',
                    ],
                    hoverBackgroundColor: [
                        '#F59E0B',
                        '#0284C7',
                        '#EF4444',
                    ],
                }]
            },
            plugins: [drawColumnDataNumber]
        });

        var data =  {
            labels: ['Ca sáng', 'Ca chiều', 'Ca tối'],
                datasets: [{
                data: [response.morningMonth, response.afternoonMonth, response.nightMonth],
                backgroundColor: [
                    '#F59E0B',
                    '#0284C7',
                    '#EF4444',
                ],
                hoverBackgroundColor: [
                    '#F59E0B',
                    '#0284C7',
                    '#EF4444',
                ],
            }]
        }
        currentMonthChart.data = data;
        currentMonthChart.update();

    });

    $(function() {
        $("#revenue").change(function() {
            var picker_value = $(this).val();
            if(!picker_value) return;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post('/sales-purchases/chart-data', {picker_value : picker_value}).done(
              function (response) {
                  var label = [];

                  if (picker_value == 'Monthly'){
                      var start = 1;
                      var end = new Date().getMonth();
                      var year = new Date().getFullYear();
                      for (i = start;i<=end+1;i++){
                          label.push(i);
                      }
                  } else if (picker_value == 'Yearly'){
                      var start = 2024;
                      var end = new Date().getFullYear();
                      for (i = start;i<=end;i++){
                          label.push(i);
                      }
                  } else  {
                      var end = new Date().getDate();
                      var month = new Date().getMonth();
                      for (var i = 1; i<=end;i++){
                          label.push(i);
                      }
                  };

                  var datas = [];
                  var lables = [];
                  var datas_custom = [];

                  var dataset = [];
                  var dataset_custom = [];

                  $.each(label, function (i, item) {
                      datas[" " + item] = 0;
                      datas_custom[" " + item] = {
                          "Bank Transfer": 0,
                          "Cash": 0
                      };
                  })

                  response.sales.original.statistics_data.forEach(function (item, idx) {
                      if (item.statistic_day !== 0) {
                          var date = item.statistic_day;
                          if(picker_value != 'Monthly' && picker_value != 'Yearly') {
                              date = new Date(item.statistic_day).getDate();
                          }
                          if (!datas_custom[" " +date]) {
                              datas_custom[" " +date] = {};
                          }
                          datas_custom[" " + date]['Count'] = parseInt(item.count);
                      }
                  });

                  response.sales.original.data.forEach(function (item, idx) {
                      var date = item.date;
                      if(picker_value != 'Monthly' && picker_value != 'Yearly') {
                           date = new Date(item.date).getDate();
                      }

                      if (!datas[" " + date]) {
                          datas[" " + date] = parseInt(item.count) / 100;
                      } else {
                          datas[" " + date] += parseInt(item.count) / 100;
                      }
                      if (!datas_custom[" " + date]) {
                          datas_custom[" " + date] = {};
                      }
                      datas_custom[" " + date][item.payment_method] = parseInt(item.count) / 100;
                  });

                  $.each(label, function (i, item) {
                      var label_item = picker_value == 'Monthly' ? "Tháng " + item : item;
                      lables.push(label_item)
                      dataset[i] = datas[" " + item] || 0;
                      dataset_custom[i] = datas_custom[" " + item] || {
                          "Bank Transfer": 0,
                          "Cash": 0
                      };
                  })
                  var  data = {
                              labels: lables,
                              datasets: [{
                                  label: 'Doanh thu',
                                  data: dataset,
                                  customData: dataset_custom,
                                  backgroundColor: [
                                      '#6366F1',
                                  ],
                                  borderColor: [
                                      '#6366F1',
                                  ],
                                  borderWidth: 1
                              }
                              ]
                          }
                  salesPurchasesChart.data = data;
                  salesPurchasesChart.update();
              }
            );
        });

        $('#monthInput').change(function (){
            var inputString = $('#monthInput').val();
            if(!inputString) return;
            const parts = inputString.split('-');
            const year = parseInt(parts[0], 10); // Convert year part to a number
            const month = parseInt(parts[1], 10); // Convert month part to a number
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post('/sales-purchases/chart-data', {picker_value : 'Daily_Month', month, year}).done(
                function (response) {
                    var label = [];
                    var end = new Date(year, month, 0).getDate();

                    for (var i = 1; i<=end;i++){
                        label.push(i);
                    }

                    var datas = [];
                    var lables = [];
                    var datas_custom = [];

                    var dataset = [];
                    var dataset_custom = [];

                    $.each(label, function (i, item) {
                        datas[" " + item] = 0;
                        datas_custom[" " + item] = {
                            "Bank Transfer": 0,
                            "Cash": 0,
                            "Count": 0
                        };
                    })

                    response.sales.original.statistics_data.forEach(function (item, idx) {
                        if (item.statistic_day !== 0) {
                            var date = new Date(item.statistic_day).getDate();
                            if (!datas_custom[" " +date]) {
                                datas_custom[" " +date] = {};
                            }
                            datas_custom[" " + date]['Count'] = parseInt(item.count);
                        }
                    });

                    var total = 0;
                    response.sales.original.data.forEach(function (item, idx) {
                        var date = item.date;
                        date = new Date(item.date).getDate();
                        total += parseInt(item.count) / 100;
                        if (!datas[" " + date]) {
                            datas[" " + date] = parseInt(item.count) / 100;
                        } else {
                            datas[" " + date] += parseInt(item.count) / 100;
                        }
                        if (!datas_custom[" " + date]) {
                            datas_custom[" " + date] = {};
                        }
                        datas_custom[" " + date][item.payment_method] = parseInt(item.count) / 100;
                    });

                    $.each(label, function (i, item) {
                        var label_item =  item;
                        lables.push(label_item)
                        dataset[i] = datas[" " + item] || 0;
                        dataset_custom[i] = datas_custom[" " + item] || {
                            "Bank Transfer": 0,
                            "Cash": 0,
                            "Count": 0
                        };
                    })
                    var  data = {
                        labels: lables,
                        datasets: [{
                            label: 'Doanh thu tổng trong tháng ' + month + "-" + year +" là: " + formatPrice(total),
                            data: dataset,
                            customData: dataset_custom,
                            backgroundColor: [
                                '#6366F1',
                            ],
                            borderColor: [
                                '#6366F1',
                            ],
                            borderWidth: 1
                        }
                        ]
                    }
                    salesPurchasesChart.data = data;
                    salesPurchasesChart.update();
                }
            );
        });

        $('#monthInputDoughnut').change(function (){
            var inputString = $('#monthInputDoughnut').val();
            if(!inputString) return;
            const parts = inputString.split('-');
            const year = parseInt(parts[0], 10); // Convert year part to a number
            const month = parseInt(parts[1], 10); // Convert month part to a number
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post('/current-month/chart-data', {month, year}).done(
                function (response) {
                    var data =  {
                        labels: ['Ca sáng', 'Ca chiều', 'Ca tối'],
                        datasets: [{
                            data: [response.morningMonth, response.afternoonMonth, response.nightMonth],
                            backgroundColor: [
                                '#F59E0B',
                                '#0284C7',
                                '#EF4444',
                            ],
                            hoverBackgroundColor: [
                                '#F59E0B',
                                '#0284C7',
                                '#EF4444',
                            ],
                        }]
                    }
                    currentMonthChart.data = data;
                    currentMonthChart.update();
                }
            );
        });
    });
});
