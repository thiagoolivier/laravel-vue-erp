<template>
    <div class="chart-card">
        <Line :data="data" :options="options">Content couldn't be loaded.</Line>
    </div>
</template>

<script setup lang="ts">
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend    
} from 'chart.js'
import { color } from 'chart.js/helpers';
import { Line } from 'vue-chartjs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

const data = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
        {
            label: 'Sale Qty (Month)',
            backgroundColor: '#9134b3',
            data: [40, 39, 10, 40, 39, 80, 40],
            tension: 0.4
        }
    ]
}

const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        title: {
            display: true,
            text: 'Sales Line Chart'
        },
    },
    scales: {
        y: {
            min: 0
        },        
    }
}
</script>

<style scoped>
</style>