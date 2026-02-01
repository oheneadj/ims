import 'flyonui/flyonui';
import ApexCharts from 'apexcharts';

import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
);

import _ from 'lodash';
window._ = _;
import { buildChart } from 'flyonui/dist/helper-apexcharts';
window.buildChart = buildChart;

window.ApexCharts = ApexCharts;
window.FilePond = FilePond;

document.addEventListener('DOMContentLoaded', function () {
    // Revenue Chart
    const revenueChartEl = document.querySelector('#revenueChart');
    if (revenueChartEl) {
        const seriesData = JSON.parse(revenueChartEl.dataset.series || '[]');
        const categories = JSON.parse(revenueChartEl.dataset.categories || '[]');

        const revenueChartOptions = {
            series: [{
                name: 'Revenue',
                data: seriesData
            }],
            chart: {
                height: 320,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: categories,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            colors: ['#4f46e5'], // Primary
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 4,
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return '₵' + val.toLocaleString();
                    }
                }
            }
        };

        const revenueChart = new ApexCharts(revenueChartEl, revenueChartOptions);
        revenueChart.render();
    }

    // Traffic/Customer Chart (Donut -> Bar/Line for now based on data available)
    // Note: Livewire is passing Customer Acquisition Counts (Array of ints)
    // Changing to a Bar chart for monthly customer acquisition
    const trafficChartEl = document.querySelector('#trafficChart');
    if (trafficChartEl) {
        const seriesData = JSON.parse(trafficChartEl.dataset.series || '[]');

        const trafficChartOptions = {
            series: [{
                name: 'New Customers',
                data: seriesData
            }],
            chart: {
                type: 'bar',
                height: 260,
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            colors: ['#8b5cf6'], // Secondary
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '50%',
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // Simplified for now
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 4,
            },
        };


        const trafficChart = new ApexCharts(trafficChartEl, trafficChartOptions);
        trafficChart.render();
    }

    if (window.HSStaticMethods) {
        window.HSStaticMethods.autoInit();
    }
});

// Re-initialize FlyonUI components after Livewire navigation
document.addEventListener('livewire:navigated', () => {
    if (window.HSStaticMethods) {
        window.HSStaticMethods.autoInit();
    }
});
