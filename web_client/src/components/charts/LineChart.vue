<template>
    <div class="chart-card">
        <div class="chart-title min-h-[20%]">
            <span class="self-center">{{ props.title }}</span>
        </div>
        <div class="min-h-[80%] p-4">
            <Line :data="props.chartData" :options="options">Content couldn't be loaded.</Line>
        </div>
    </div>
</template>

<script setup lang="ts">
import {
    Chart,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    type ChartData,
    type ChartOptions
} from 'chart.js';
import { Line } from 'vue-chartjs';

Chart.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

const props = defineProps<{
    title: string;
    chartData: ChartData<'line'>;
}>();

const options: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
    },
    scales: {
        y: {
            grid: {
                display: false
            },
        }    
    }
};
</script>

<style scoped>
canvas {
    background-color: inherit;
}
</style>