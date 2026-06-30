    const sales_chart = new ApexCharts(document.querySelector('#revenue-chart'), {
      series: [{
        name: 'Digital Goods',
        data: [28, 48, 40, 19, 86, 27, 90]
      }, {
        name: 'Electronics',
        data: [65, 59, 80, 81, 56, 55, 40]
      }, {
        name: 'Fashion',
        data: [10, 20, 30, 40, 50, 60, 70]
      }],
      
      chart: {
        height: 300,
        type: 'area',
        toolbar: {
          show: false
        }
      },
      legend: {
        show: false
      },
      colors: ['#0d6efd', '#20c997', '#fafa00'],
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth'
      },
      xaxis: {
        type: 'datetime',
        categories: ['2026-01-01', '2026-02-01', '2026-03-01', '2026-04-01', '2026-05-01', '2026-06-01', '2026-07-01']
      },
      tooltip: {
        x: {
          format: 'MMMM yyyy'
        }
      },
    });
    sales_chart.render();