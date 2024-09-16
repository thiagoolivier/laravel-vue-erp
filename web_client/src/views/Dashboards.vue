<template>
    <div class="grid grid-cols-1 md:grid-cols-2 md:grid-rows-4 gap-4 p-4 min-h-full">
        <BarChart :title="barChartTitle" :chartData="data" class="row-span-2" />
        <LineChart :title="lineChartTitle" :chartData="data" class="row-span-2"/>
    </div>
</template>

<script setup lang="ts">
import BarChart from '@/components/charts/BarChart.vue';
import LineChart from '@/components/charts/LineChart.vue';
import { Chart } from "chart.js";

// General settings...
Chart.defaults.font.size = 11;
Chart.defaults.font.family = 'Kanit';
Chart.defaults.font.style = 'normal';
Chart.defaults.font.weight = 'lighter';
Chart.defaults.color = '#f0f0f0';

const barChartTitle = 'Sales (Bar)';
const lineChartTitle = 'Sales (Line)';

const data = {
    labels: [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Nov',
        'Dec'
    ],
    datasets: [
        {
            label: 'Subsidiary Grape',
            borderColor: 'rgba(188, 158, 243, 1)',
            backgroundColor: 'rgba(188, 158, 243, 1)',
            data: [40, 39, 10, 40, 39, 80, 40, 35, 60, 20, 50, 70],
            tension: 0.4,
        },
        {
            label: 'Subsidiary Apple',
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            data: [50, 60, 70, 80, 90, 100, 110, 90, 80, 75, 85, 95],
            tension: 0.3,
        },
        {
            label: 'Subsidiary Orange',
            borderColor: 'rgba(255, 159, 64, 1)',
            backgroundColor: 'rgba(255, 159, 64, 0.5)',
            data: [30, 25, 45, 60, 55, 70, 65, 40, 50, 30, 45, 55],
            tension: 0.5,
        },
        {
            label: 'Subsidiary Tulipe',
            borderColor: 'rgba(153, 102, 255, 1)',
            backgroundColor: 'rgba(153, 102, 255, 0.3)',
            data: [20, 30, 50, 40, 70, 60, 90, 85, 95, 100, 110, 105],
            tension: 0.7,
        }
    ]
};

</script>